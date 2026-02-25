<?php

// app/Observers/CitaObserver.php
namespace App\Observers;

use App\Mail\AppointmentApproved;
use App\Mail\AppointmentCancelled;
use App\Models\Cita;
use App\Models\TenantInfo;
use App\Models\TenantSetting;
use Illuminate\Support\Facades\Mail;

class CitaObserver
{
    public function updated(Cita $cita): void
    {
        if (!$cita->wasChanged('status')) return;

        $old    = $cita->getOriginal('status');
        $new    = $cita->status;
        $client = $cita->client;

        // Aprobada (confirmada) → notificar al cliente
        if ($old !== 'confirmed' && $new === 'confirmed' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentApproved($cita));
        }

        // Cancelada → notificar al cliente
        if ($old !== 'cancelled' && $new === 'cancelled' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentCancelled($cita));
        }

        // No-show → calcular deuda pendiente
        if ($new === 'not_arrive' && $cita->client_id && $client) {
            $tenantId        = TenantInfo::first()->tenant;
            $settings_barber = TenantSetting::where('tenant_id', $tenantId)->first();
            $total_cents     = $cita->total_cents / 100;
            $porc            = (int) round(($settings_barber->no_show_fee_cents ?? 0) / 100);
            $due             = $total_cents * ($porc / 100);
            $client->update(['due_price' => $due]);
        }
    }

}

