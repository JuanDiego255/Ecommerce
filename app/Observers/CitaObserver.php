<?php

// app/Observers/CitaObserver.php
namespace App\Observers;

use App\Mail\AppointmentApproved;
use App\Mail\AppointmentCancelled;
use App\Models\Cita;
use Illuminate\Support\Facades\Mail;

class CitaObserver
{
    public function updated(Cita $cita): void
    {
        if (!$cita->wasChanged('status')) return;
        $old = $cita->getOriginal('status');
        $new = $cita->status;
        // Aprobada (confirmada)
        if ($old !== 'confirmed' && $new === 'confirmed' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentApproved($cita));
        }

        // Cancelada
        if ($old !== 'cancelled' && $new === 'cancelled' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentCancelled($cita));
        }
    }
}
