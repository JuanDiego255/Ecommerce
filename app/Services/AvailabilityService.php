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
        if ($barbero->excepciones()->whereDate('date', $dateYmd)->exists()) return [];

        $start = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workStart);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workEnd);

        // --- Regla especial sábado: último inicio 17:45
        $isSaturday   = ($dayIdx === 6);
        $lastStartSat = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' 17:45');

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
        $now    = Carbon::now(); // Considera TZ: Carbon::now(config('app.timezone'))
        $isToday = $dateYmd === $now->toDateString();

        // Función de traslape con arrays de intervalos
        $overlaps = function ($startA, $endA, $startB, $endB) {
            return ($startA < $endB) && ($endA > $startB);
        };

        $available = [];

        // End efectivo (para validar candidateEnd). En sábado, permitimos que el último
        // inicio (17:45) sea válido aunque su duración+buffer pase el work_end,
        // extendiendo sólo la validación (no el horario real).
        $effectiveEnd = $end->copy();
        if ($isSaturday) {
            $endNeededForLast = $lastStartSat->copy()->addMinutes($requiredMinutes + $buffer);
            if ($endNeededForLast->gt($effectiveEnd)) {
                $effectiveEnd = $endNeededForLast;
            }
        }

        foreach ($slots as $candidate) {
            // Limitar sábados: no permitir inicios posteriores a 17:45
            if ($isSaturday && $candidate->gt($lastStartSat)) {
                continue;
            }

            // Si es hoy, ocultar slots ya pasados
            if ($isToday && $candidate->lt($now)) {
                continue;
            }

            $candidateStart = $candidate->copy();
            // Duración requerida + buffer al final
            $candidateEnd   = $candidate->copy()->addMinutes($requiredMinutes + $buffer);

            // Validación de tope por fin de jornada (usamos end efectivo)
            if ($candidateEnd->gt($effectiveEnd)) {
                continue;
            }

            // Traslape con citas
            $busy = false;
            foreach ($citas as $c) {
                if ($overlaps($candidateStart, $candidateEnd, $c->starts_at, $c->ends_at)) {
                    $busy = true;
                    break;
                }
            }
            if ($busy) continue;

            // Traslape con bloques
            foreach ($bloques as $b) {
                if ($overlaps($candidateStart, $candidateEnd, $b['starts_at'], $b['ends_at'])) {
                    $busy = true;
                    break;
                }
            }
            if ($busy) continue;

            $available[] = $candidate->format('H:i');
        }

        return $available;
    }
}
