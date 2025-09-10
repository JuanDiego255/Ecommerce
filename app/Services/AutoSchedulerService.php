<?php

namespace App\Services;

use App\Models\{Client, Barbero, Cita};
use App\Support\TenantSettings;
use Carbon\Carbon;

class AutoSchedulerService
{
    public function findBestSlotFor(Client $client): ?array
    {
        // 1) Config y preferencias
        $tz = config('app.timezone', 'America/Costa_Rica');
        $tenantId = tenant('id') ?? config('app.name'); // ajusta según tu tenancy
        $tenant = TenantSettings::get($tenantId);
        if (!$tenant || !$tenant->auto_book_enabled) return null;

        $cadence = (int)($client->cadence_days ?: $tenant->auto_book_default_cadence_days ?: 30);
        $lookbackDays = (int)($tenant->auto_book_lookback_days ?: 90);
        $minVisits = (int)($tenant->auto_book_min_visits ?: 3);

        // 2) Chequear visitas completadas en ventana
        $from = now()->subDays($lookbackDays);
        $visits = Cita::where('client_id', $client->id)->where('status', 'completed')
            ->where('starts_at', '>=', $from)->count();
        if ($visits < $minVisits) return null;

        // 3) Próxima fecha objetivo por cadencia (desde la última completada)
        $lastDone = Cita::where('client_id', $client->id)->where('status', 'completed')
            ->orderByDesc('starts_at')->first();
        $target = $lastDone ? $lastDone->starts_at->copy()->timezone($tz)->addDays($cadence) : now($tz)->addDays(7);
        if (isset($client->next_due_at) && $client->next_due_at instanceof \Carbon\Carbon && now()->lt($client->next_due_at)) {
            // aún no toca proponer; evita spam si corres cada hora
            return null;
        }

        // 4) Barbero base
        $barbero = $client->preferredBarbero ?: Barbero::where('activo', 1)->orderBy('id')->first();
        if (!$barbero) return null;

        // 5) Ventana de búsqueda (ej. target ± 14 días)
        $startWindow = $target->copy()->subDays(7);
        $endWindow   = $target->copy()->addDays(14);

        // 6) Preferencias del cliente
        $prefDays   = $client->preferred_days ?: [1, 2, 3, 4, 5]; // default L-V
        $prefStart  = substr($client->preferred_start ?: '09:00', 0, 5);
        $prefEnd    = substr($client->preferred_end   ?: '18:00', 0, 5);

        // 7) Slot size (tu regla general)
        $slotMin = (int)($barbero->slot_minutes ?: 30);
        $duration = $this->inferDurationForClient($client, $barbero) ?: $slotMin;

        // 8) Buscar primer hueco que cumpla reglas
        $best = $this->scanAvailability($barbero, $startWindow, $endWindow, $prefDays, $prefStart, $prefEnd, $duration, $tz);
        return $best; // ['start'=>$startLocalCarbon, 'end'=>$endLocalCarbon, 'barbero'=>$barbero]
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

    protected function scanAvailability(Barbero $barbero, Carbon $from, Carbon $to, array $prefDays, string $prefStart, string $prefEnd, int $duration, string $tz): ?array
    {
        // Recorre día por día y construye slots dentro de [prefStart, prefEnd],
        // aplicando: workDays, work_start/end, excepciones, bloques, buffer, solapes con citas.
        // Devuelve el primer slot válido.
        // (Reusa la lógica que ya tienes para “getAvailableSlots” o similar).

        // PSEUDOCÓDIGO (implementa igual a tu Booking/CalendarController):
        $day = $from->copy();
        while ($day->lte($to)) {
            $dow = $day->dayOfWeek; // 0..6
            if (in_array($dow, $prefDays)) {
                $slotStart = Carbon::createFromFormat('Y-m-d H:i', $day->toDateString() . ' ' . $prefStart, $tz);
                $slotEnd   = Carbon::createFromFormat('Y-m-d H:i', $day->toDateString() . ' ' . $prefEnd,   $tz);
                // iterar cada slot del barbero dentro de ese rango y validar con tus helpers
                // si encuentras hueco: return ['start'=>$candidateStart,'end'=>$candidateEnd,'barbero'=>$barbero];
            }
            $day->addDay();
        }
        return null;
    }
}
