<?php

namespace App\Services;

use App\Models\{Client, Barbero, Cita};
use App\Support\TenantSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoSchedulerService
{
    public function findBestSlotFor(Client $client, $activeInUpdate = false): ?array
    {
        $result = null;
        if (!$activeInUpdate) {
            $result = $this->findAuto($client);
        } else {
            $result = $this->findInUpdate($client);
        }
        return $result;
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
    public function findAuto(Client $client): ?array
    {

        // 1) Config y preferencias
        $tz = config('app.timezone', 'America/Costa_Rica');
        $tenantId = tenant('id') ?? config('app.name');
        $tenant = TenantSettings::get($tenantId);
        if (!$tenant || $tenant->auto_book_enabled != 1) return null;

        $effectiveCadence = $client->cadence_days
            ?: ($client->auto_book_frequency === 'weekly' ? 7
                : ($client->auto_book_frequency === 'biweekly' ? 14
                    : ($tenant->auto_book_default_cadence_days ?? 30)));
        $lookback    = (int)($tenant->auto_book_lookback_days ?: 90);
        $minVisits   = (int)($tenant->auto_book_min_visits ?: 3);

        // 2) Chequear visitas completadas en ventana
        $from = now($tz)->subDays($lookback)->startOfDay();
        $visits = Cita::where('client_id', $client->id)
            ->where('status', 'completed')
            ->where('starts_at', '>=', $from) // si guardas UTC; si no, ajusta
            ->count();
        if (
            !$client->prefersWeekly() &&
            !$client->prefersBiweekly() &&
            $visits < $minVisits
        ) {
            return null;
        }

        // 3) Próxima fecha objetivo por cadencia
        $lastDone = Cita::where('client_id', $client->id)
            ->where('status', 'completed')
            ->orderByDesc('starts_at')
            ->first();

        $target = $lastDone
            ? $lastDone->starts_at->copy()->timezone($tz)->addDays($effectiveCadence)
            : now($tz)->addDays(min($effectiveCadence, 7));

        $lookbehindDays = $client->auto_book_frequency === 'weekly' ? 3 : 7;
        $lookaheadDays  = $client->auto_book_frequency === 'weekly' ? 10 : 21;

        if ($client->next_due_at instanceof \Carbon\Carbon && now($tz)->lt($client->next_due_at->timezone($tz))) {
            return null; // aún no toca proponer
        }

        // 4) Barbero base (preferido o cualquiera activo)
        $barbero = $client->preferredBarbero ?: Barbero::where('activo', 1)->orderBy('id')->first();
        if (!$barbero) return null;
        // 5) Ventana de búsqueda alrededor del target
        $startWindow = $target->copy()->subDays($lookbehindDays)->startOfDay();
        $endWindow   = $target->copy()->addDays($lookaheadDays)->endOfDay();

        // 6) Preferencias del cliente (fallbacks sensatos)
        $prefDays  = $client->preferred_days ?: [1, 2, 3, 4, 5, 6]; // L-S
        $prefStart = substr($client->preferred_start ?: '09:00', 0, 6);
        $prefEnd   = substr($client->preferred_end   ?: '18:00', 0, 6);

        // 7) Duración a usar (según patrón o slot del barbero)
        $slotMin  = (int)($barbero->slot_minutes ?: 30);
        $duration = $this->inferDurationForClient($client, $barbero) ?: $slotMin;
        // 8) Buscar primer hueco válido usando availableSlots (convertido a Carbon)
        $result = $this->scanAvailabilityUsingAvailableSlots(
            $barbero,
            $startWindow,
            $endWindow,
            $prefDays,
            $prefStart,
            $prefEnd,
            $duration,
            $tz
        );

        if (!$result) {
            $extra = $client->auto_book_frequency === 'weekly' ? 7 : 14;
            $result = $this->scanAvailabilityUsingAvailableSlots(
                $barbero,
                $endWindow->copy()->addDay(),
                $endWindow->copy()->addDays($extra),
                $prefDays,
                $prefStart,
                $prefEnd,
                $duration,
                $tz
            );
        }
        return $result;
    }
    public function findInUpdate(Client $client): ?array
    {
        // Requiere weekly o biweekly
        if (!$client->prefersWeekly() && !$client->prefersBiweekly()) {
            return null;
        }

        // 1) Config y habilitación
        $tz = config('app.timezone', 'America/Costa_Rica');
        $tenantId = tenant('id') ?? config('app.name');
        $tenant = TenantSettings::get($tenantId);
        if (!$tenant || $tenant->auto_book_enabled != 1) return null;

        // 2) Última cita como ancla (si no hay, usar ahora)
        $lastDone = Cita::where('client_id', $client->id)
            ->where('status', 'completed')
            ->orderByDesc('starts_at')
            ->first();

        $anchor = $lastDone
            ? $lastDone->starts_at->copy()->timezone($tz)
            : now($tz);

        // 3) Barbero base
        $barbero = $client->preferredBarbero ?: Barbero::where('activo', 1)->orderBy('id')->first();
        if (!$barbero) return null;

        // 4) Preferencias de día/hora
        // ISO: 1=Lun ... 6=Sáb, 7=Dom (manteniendo tu convención L-S en [1..6])
        $prefDays = $client->preferred_days;
        if (!$prefDays || !is_array($prefDays) || count($prefDays) === 0) {
            // Si no hay día preferido definido, no forzamos este flujo
            return null;
        }
        // Normaliza enteros 1..7 y ordena único
        $prefDays = array_values(array_unique(array_map('intval', $prefDays)));

        // Horas normalizadas
        $prefStart = $client->preferred_start
            ? \Carbon\Carbon::parse($client->preferred_start, $tz)->format('H:i')
            : '09:00';
        $prefEnd = $client->preferred_end
            ? \Carbon\Carbon::parse($client->preferred_end, $tz)->format('H:i')
            : '18:00';

        // Duración: si definieron start/end, usamos la diferencia; si no, inferimos
        $slotMin  = (int)($barbero->slot_minutes ?: 30);
        if ($client->preferred_start && $client->preferred_end) {
            $duration = \Carbon\Carbon::parse($client->preferred_start, $tz)
                ->diffInMinutes(\Carbon\Carbon::parse($client->preferred_end, $tz));
            if ($duration <= 0) $duration = $slotMin;
        } else {
            $duration = $this->inferDurationForClient($client, $barbero) ?: $slotMin;
        }

        // 5) Helper: siguiente fecha cuyo dayOfWeekIso ∈ $prefDays (estrictamente después de $anchor)
        $nextIsoDayAfter = function (\Carbon\Carbon $from, array $isoDays) use ($tz): \Carbon\Carbon {
            $cursor = $from->copy()->addDay()->startOfDay(); // estrictamente después
            // Buscamos el más cercano dentro de los próximos 14 días (suficiente para cubrir multi-días)
            $best = null;
            for ($i = 0; $i < 14; $i++) {
                if (in_array($cursor->dayOfWeekIso, $isoDays, true)) {
                    $best = $cursor->copy();
                    break;
                }
                $cursor->addDay();
            }
            return $best ?: $from->copy()->addWeek()->startOfDay(); // fallback defensivo
        };

        // 6) Primer intento: el siguiente día preferido después de la última cita
        $firstTryDate = $nextIsoDayAfter($anchor, $prefDays);

        // 7) Segundo intento: +1 semana (weekly) o +2 semanas (biweekly) desde ese mismo día
        $weeks = $client->prefersWeekly() ? 1 : 2;
        $secondTryDate = $firstTryDate->copy()->addWeeks($weeks);

        // 8) Intentos dirigidos (dos chances máximo). Ventana = exclusivamente ese día.
        $attempts = [$firstTryDate, $secondTryDate];

        foreach ($attempts as $tryDate) {
            // Limitamos la búsqueda al día completo del intento
            $startWindow = $tryDate->copy()->startOfDay();
            $endWindow   = $tryDate->copy()->endOfDay();

            // Forzamos SOLO el día ISO del intento para que no se deslice a otro día
            $onlyThisDay = [$tryDate->dayOfWeekIso];

            $result = $this->scanAvailabilityUsingAvailableSlots(
                $barbero,
                $startWindow,
                $endWindow,
                $onlyThisDay,
                $prefStart,
                $prefEnd,
                $duration,
                $tz
            );

            if ($result) {
                // (Opcional) Si quieres exigir que inicie exactamente en $prefStart,
                // valida aquí: if ($result['start']->format('H:i') !== $prefStart) { continue; }
                return $result;
            }
        }

        // 9) Si no hubo hueco en esos dos intentos, nos detenemos.
        return null;
    }
}
