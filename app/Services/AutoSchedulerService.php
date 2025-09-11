<?php

namespace App\Services;

use App\Models\{Client, Barbero, Cita};
use App\Support\TenantSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoSchedulerService
{
    public function findBestSlotFor(Client $client): ?array
    {
        // 1) Config y preferencias
        $tz = config('app.timezone', 'America/Costa_Rica');
        $tenantId = tenant('id') ?? config('app.name');
        $tenant = TenantSettings::get($tenantId);
        if (!$tenant || $tenant->auto_book_enabled != 1) return null;

        $cadence     = (int)($client->cadence_days ?: $tenant->auto_book_default_cadence_days ?: 30);
        $lookback    = (int)($tenant->auto_book_lookback_days ?: 90);
        $minVisits   = (int)($tenant->auto_book_min_visits ?: 3);

        // 2) Chequear visitas completadas en ventana
        $from = now($tz)->subDays($lookback)->startOfDay();
        $visits = Cita::where('client_id', $client->id)
            ->where('status', 'completed')
            ->where('starts_at', '>=', $from) // si guardas UTC; si no, ajusta
            ->count();
        if ($visits < $minVisits) return null;

        // 3) Próxima fecha objetivo por cadencia
        $lastDone = Cita::where('client_id', $client->id)
            ->where('status', 'completed')
            ->orderByDesc('starts_at')
            ->first();

        $target = $lastDone
            ? $lastDone->starts_at->copy()->timezone($tz)->addDays($cadence)
            : now($tz)->addDays(7);

        if ($client->next_due_at instanceof \Carbon\Carbon && now($tz)->lt($client->next_due_at->timezone($tz))) {
            return null; // aún no toca proponer
        }

        // 4) Barbero base (preferido o cualquiera activo)
        $barbero = $client->preferredBarbero ?: Barbero::where('activo', 1)->orderBy('id')->first();
        if (!$barbero) return null;
        // 5) Ventana de búsqueda alrededor del target
        $startWindow = $target->copy()->subDays(7)->startOfDay();
        $endWindow   = $target->copy()->addDays(14)->endOfDay();

        // 6) Preferencias del cliente (fallbacks sensatos)
        $prefDays  = $client->preferred_days ?: [1, 2, 3, 4, 5]; // L-V
        $prefStart = substr($client->preferred_start ?: '09:00', 0, 5);
        $prefEnd   = substr($client->preferred_end   ?: '18:00', 0, 5);

        // 7) Duración a usar (según patrón o slot del barbero)
        $slotMin  = (int)($barbero->slot_minutes ?: 30);
        $duration = $this->inferDurationForClient($client, $barbero) ?: $slotMin;
        // 8) Buscar primer hueco válido usando availableSlots (convertido a Carbon)
        return $this->scanAvailabilityUsingAvailableSlots(
            $barbero,
            $startWindow,
            $endWindow,
            $prefDays,
            $prefStart,
            $prefEnd,
            $duration,
            $tz
        );
    }
    protected function inferDurationForClient(Client $client, Barbero $barbero): ?int
    {
        // Puedes inferir a partir de últimos servicios del cliente con ese barbero o un default.
        $last = Cita::where('client_id', $client->id)->where('barbero_id', $barbero->id)
            ->whereIn('status', ['confirmed', 'completed'])->orderByDesc('starts_at')->first();
        if ($last && $last->ends_at && $last->starts_at) {
            return $last->ends_at->diffInMinutes($last->starts_at);
        }
        return null;
    }
    /**
     * Busca el primer hueco usando availableSlots, respetando días/horas preferidas
     * y un rango de fechas. Devuelve ['start'=>Carbon,'end'=>Carbon,'barbero'=>Barbero] o null.
     */
    public function scanAvailabilityUsingAvailableSlots(
        Barbero $barbero,
        Carbon $from,
        Carbon $to,
        array $prefDays,      // ej [1,2,3,4,5] (0=Dom..6=Sáb)
        string $prefStart,    // "09:00"
        string $prefEnd,      // "18:00"
        int $requiredMinutes, // duración deseada
        string $tz            // "America/Costa_Rica"
    ): ?array {
        $day = $from->copy()->startOfDay();
        $prefDaysArray = array_map('intval', $prefDays);
        while ($day->lte($to)) {
            $dow = $day->dayOfWeek; // 0..6
            if (!in_array($dow, $prefDaysArray, true)) {
                $day->addDay();
                continue;
            }
            // Ventana horaria preferida del cliente (en TZ local)
            $winStart = Carbon::createFromFormat('Y-m-d H:i', $day->toDateString() . ' ' . substr($prefStart, 0, 5), $tz);
            $winEnd   = Carbon::createFromFormat('Y-m-d H:i', $day->toDateString() . ' ' . substr($prefEnd, 0, 5),   $tz);
            if ($winStart->gte($winEnd)) { // ventana inválida
                $day->addDay();
                continue;
            }
            // Horas disponibles de ese día como Carbon
            $hhmmList = $this->availableSlots($barbero, $day->toDateString(), $requiredMinutes, $tz);

            foreach ($hhmmList  as $hhmm) {
                // Filtra por ventana preferida

                $candidateStart = Carbon::createFromFormat('Y-m-d H:i', $day->toDateString() . ' ' . $hhmm, $tz);
                $candidateEnd   = $candidateStart->copy()->addMinutes($requiredMinutes);

                if ($candidateStart->lt($winStart) || $candidateStart->gte($winEnd)) {
                    continue;
                }
                $candidateEnd = $candidateStart->copy()->addMinutes($requiredMinutes);
                // (Opcional) Sanity check extra si quieres revalidar solapes/bloques:
                // if ($this->hasOverlap($barbero, $candidateStart, $candidateEnd, $tz)) continue;
                return [
                    'start'   => $candidateStart,
                    'end'     => $candidateEnd,
                    'barbero' => $barbero,
                ];
            }

            $day->addDay();
        }

        return null;
    }
    public function availableSlots(Barbero $barbero, string $dateYmd, int $requiredMinutes, string $tz): array
    {
        $slot       = (int)($barbero->slot_minutes ?? 30);
        $buffer     = (int)($barbero->buffer_minutes ?? 0);
        $workStart  = substr($barbero->work_start ?? '09:00', 0, 5);
        $workEnd    = substr($barbero->work_end   ?? '18:00', 0, 5);
        $workDays   = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];

        $dayIdx = (int)Carbon::parse($dateYmd, $tz)->dayOfWeek;
        if (!in_array($dayIdx, $workDays ?? [])) return [];

        // Excepción: día libre/feriado
        if ($barbero->excepciones()->whereDate('date', $dateYmd)->exists()) return [];

        $start = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workStart, $tz);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workEnd,   $tz);

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
            ->map(function ($b) use ($dateYmd, $tz) {
                return [
                    'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->start_time, 0, 5), $tz),
                    'ends_at'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->end_time,   0, 5), $tz),
                ];
            });

        // Generar slots base
        $slots = [];
        for ($t = $start->copy(); $t->lt($end); $t->addMinutes($slot)) {
            $slots[] = $t->copy();
        }

        // Función de traslape con arrays de intervalos
        $overlaps = function ($startA, $endA, $startB, $endB) {
            return ($startA < $endB) && ($endA > $startB);
        };

        $available = [];
        foreach ($slots as $candidate) {
            $candidateStart = $candidate->copy();
            // Duración requerida + buffer al final
            $candidateEnd   = $candidate->copy()->addMinutes($requiredMinutes + $buffer);
            if ($candidateEnd->gt($end)) continue;

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
