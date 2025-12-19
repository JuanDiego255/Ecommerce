<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Barbero;
use Carbon\Carbon;


class AvailabilityService
{
    public function availableSlotsOrig(Barbero $barbero, string $dateYmd, int $durationMinutes): array
    {
        $date = Carbon::createFromFormat('Y-m-d', $dateYmd)->startOfDay();
        $weekday = (int)$date->format('w');
        $workDays = collect(json_decode($barbero->work_days ?? '[]', true));
        if ($workDays->isNotEmpty() && !$workDays->contains($weekday)) return [];


        $slot = max(5, (int)($barbero->slot_minutes ?? 30));
        $start = Carbon::parse($date->toDateString() . ' ' . $barbero->work_start);
        $end = Carbon::parse($date->toDateString() . ' ' . $barbero->work_end);
        if ($end->lte($start)) return [];


        $booked = Cita::where('barbero_id', $barbero->id)
            ->whereDate('starts_at', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->get(['starts_at', 'ends_at']);


        $slots = [];
        for ($cursor = $start->copy(); $cursor->lt($end); $cursor->addMinutes($slot)) {
            $slotStart = $cursor->copy();
            $slotEnd = $cursor->copy()->addMinutes($durationMinutes);
            if ($slotEnd->gt($end)) break;
            $overlap = false;
            foreach ($booked as $c) {
                $cs = Carbon::parse($c->starts_at);
                $ce = Carbon::parse($c->ends_at);
                if ($slotStart->lt($ce) && $cs->lt($slotEnd)) {
                    $overlap = true;
                    break;
                }
            }
            if (!$overlap) $slots[] = $slotStart->format('H:i');
        }
        return $slots;
    }
    //Con excepciones de tiempo
    public function availableSlots(Barbero $barbero, string $dateYmd, int $requiredMinutes): array
    {
        $slot       = (int)($barbero->slot_minutes ?? 30);
        $buffer     = (int)($barbero->buffer_minutes ?? 0);
        $workStart  = substr($barbero->work_start ?? '09:00', 0, 5);
        $workEnd    = substr($barbero->work_end   ?? '18:00', 0, 5);
        $workDays   = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];

        $dayIdx = (int)Carbon::parse($dateYmd)->dayOfWeek; // 0=Dom..6=Sab
        if (!in_array($dayIdx, $workDays ?? [])) return [];

        // Excepción: día libre/feriado
        //if ($barbero->excepciones()->whereDate('date', $dateYmd)->exists()) return [];
        $hasException = $barbero->excepciones()
            ->whereDate('date', '<=', $dateYmd)
            ->whereDate('date_to',   '>=', $dateYmd)
            ->exists();

        if ($hasException) return [];

        $start = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workStart);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workEnd);

        // --- Reglas específicas sábado (no exceder 18:00)
        $isSaturday     = ($dayIdx === 6);
        $hardSaturdayEnd = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' 18:00');

        // Límite real de fin de jornada para validar servicios (en sábado no se puede pasar de 18:00)
        $dayEndLimit = $isSaturday
            ? (($end->lt($hardSaturdayEnd)) ? $end->copy() : $hardSaturdayEnd->copy())
            : $end->copy();

        // Último inicio permitido en sábado según la duración requerida (servicio + buffer)
        // (en otros días no aplicamos tope extra)
        $lastStartAllowedSat = $isSaturday
            ? $dayEndLimit->copy()->subMinutes($requiredMinutes + $buffer)
            : null;

        // Citas del día (que ocupan)
        $citas = Cita::where('barbero_id', $barbero->id)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->whereDate('starts_at', $dateYmd)
            ->orderBy('starts_at')
            ->get(['starts_at', 'ends_at']);

        // Bloques del día
        $bloques = $barbero->bloques()
            ->whereDate('date', $dateYmd)
            ->orderBy('start_time')
            ->get(['start_time', 'end_time'])
            ->map(function ($b) use ($dateYmd) {
                return [
                    'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->start_time, 0, 5)),
                    'ends_at'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->end_time, 0, 5)),
                ];
            });

        // Ejemplo: almuerzo fijo 12:00-13:00 (si aplica en tu lógica)
        $bloques[] = [
            'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' 12:00'),
            'ends_at'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' 13:00'),
        ];

        // Generar slots base
        $slots = [];
        for ($t = $start->copy(); $t->lt($end); $t->addMinutes($slot)) {
            $slots[] = $t->copy();
        }

        // Hora "ahora" (solo filtrar si la fecha consultada es hoy)
        $now     = Carbon::now(); // Considera TZ: Carbon::now(config('app.timezone'))
        $isToday = $dateYmd === $now->toDateString();

        // Función de traslape con arrays de intervalos
        $overlaps = function ($startA, $endA, $startB, $endB) {
            return ($startA < $endB) && ($endA > $startB);
        };

        $available = [];

        // Ya no extendemos el fin de jornada en sábado; usamos siempre $dayEndLimit
        $effectiveEnd = $dayEndLimit->copy();

        foreach ($slots as $candidate) {
            // Tope de último inicio en sábado (dinámico según duración)
            if ($isSaturday && $candidate->gt($lastStartAllowedSat)) continue;

            if ($isToday && $candidate->lt($now)) continue;

            $candidateStart = $candidate->copy();
            $candidateEnd   = $candidate->copy()->addMinutes($requiredMinutes + $buffer);

            // Validamos contra el fin real del día (en sábado no pasar de 18:00)
            if ($candidateEnd->gt($effectiveEnd)) continue;

            $busy = false;
            foreach ($citas as $c) {
                if ($overlaps($candidateStart, $candidateEnd, $c->starts_at, $c->ends_at)) {
                    $busy = true;
                    break;
                }
            }
            if ($busy) continue;

            foreach ($bloques as $b) {
                if ($overlaps($candidateStart, $candidateEnd, $b['starts_at'], $b['ends_at'])) {
                    $busy = true;
                    break;
                }
            }
            if ($busy) continue;

            // Siempre en formato 12 horas
            $available[] = $candidate->format('g:i A');
        }

        return $available;
    }
}
