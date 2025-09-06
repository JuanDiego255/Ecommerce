<?php

// app/Mail/AppointmentReminder.php
namespace App\Mail;

use App\Models\Cita;
use App\Support\IcsBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminder extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public Cita $cita) {}

    public function build()
    {
        $tz = config('app.timezone', 'America/Costa_Rica');
        $start = $this->cita->starts_at->timezone($tz)->format('Y-m-d\TH:i:s');
        $end   = $this->cita->ends_at->timezone($tz)->format('Y-m-d\TH:i:s');
        $ics = \App\Support\IcsBuilder::appointment(
            "cita-{$this->cita->id}@barberia",
            'Recordatorio de cita',
            "Barbero: {$this->cita->barbero->nombre}\nServicios: {$this->cita->resumen_servicios}",
            $start,
            $end,
            $tz
        );
        return $this->subject('🔔 Recordatorio: tu cita es mañana')
            ->view('emails.citas.reminder')
            ->attachData($ics, "cita-{$this->cita->id}.ics", ['mime' => 'text/calendar']);
    }
}
