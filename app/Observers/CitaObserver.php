<?php

// app/Observers/CitaObserver.php
namespace App\Observers;

use App\Mail\AppointmentApproved;
use App\Mail\AppointmentCancelled;
use App\Models\Cita;
use App\Support\TenantSettings;
use Illuminate\Support\Facades\Mail;

class CitaObserver
{
    public function updated(Cita $cita): void
    {
        if (!$cita->wasChanged('status')) return;
        $tz = config('app.timezone', 'America/Costa_Rica');
        $old = $cita->getOriginal('status');
        $new = $cita->status;
        $tenantId = tenant('id') ?? config('app.name'); // ajusta según tu tenancy
        $tenant = TenantSettings::get($tenantId);
        // Aprobada (confirmada)
        if ($old !== 'confirmed' && $new === 'confirmed' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentApproved($cita));
        }

        // Cancelada
        if ($old !== 'cancelled' && $new === 'cancelled' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentCancelled($cita));
        }

        if ($cita->wasChanged('status') && $cita->status === 'completed' && $cita->client_id) {
            $client = $cita->client;
            if ($client && $client->auto_book_opt_in) {

                $cadence = $client->cadence_days ?: ($tenant->auto_book_default_cadence_days ?? 30);
                $baseLocal = $cita->starts_at->copy()->timezone($tz);
                $client->update([
                    'last_seen_at' => now(),
                    'next_due_at'  => $client->computeNextDueAtFromBase($baseLocal, $cadence, $tz),
                ]);
            }
        }
    }
}
