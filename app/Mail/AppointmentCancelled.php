<?php

// app/Mail/AppointmentCancelled.php
namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelled extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public Cita $cita) {}

    public function build()
    {
        return $this->subject('❌ Tu cita fue cancelada')
            ->view('emails.citas.cancelled');
    }
}
