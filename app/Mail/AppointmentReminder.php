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
        $tenantId    = tenant('id') ?? \App\Models\TenantInfo::first()->tenant;
        $emailConfig = \App\Models\CompanyEmailSetting::where('tenant_id', $tenantId)->first();

        if ($emailConfig && $emailConfig->password !== null) {
            config([
                'mail.mailers.dynamic' => [
                    'transport'  => $emailConfig->mailer ?? 'smtp',
                    'host'       => $emailConfig->host,
                    'port'       => $emailConfig->port,
                    'encryption' => $emailConfig->encryption,
                    'username'   => $emailConfig->username,
                    'password'   => $emailConfig->password,
                    'timeout'    => null,
                    'auth_mode'  => null,
                ],
                'mail.from.address' => $emailConfig->from_address,
                'mail.from.name'    => $emailConfig->from_name ?? 'Info Barbería',
            ]);
            $this->mailer('dynamic');
            $fromAddress = $emailConfig->from_address;
            $fromName    = $emailConfig->from_name ?? 'Info Barbería';
        } else {
            $fromAddress = config('mail.from.address');
            $fromName    = config('mail.from.name', 'Info Barbería');
        }

        $tz    = config('app.timezone', 'America/Costa_Rica');
        $start = $this->cita->starts_at->timezone($tz)->format('Y-m-d\TH:i:s');
        $end   = $this->cita->ends_at->timezone($tz)->format('Y-m-d\TH:i:s');
        $ics   = \App\Support\IcsBuilder::appointment(
            "cita-{$this->cita->id}@barberia",
            'Recordatorio de cita',
            "Barbero: {$this->cita->barbero->nombre}\nServicios: {$this->cita->resumen_servicios}",
            $start,
            $end,
            $tz
        );

        return $this->from($fromAddress, $fromName)
            ->subject('🔔 Recordatorio: tu cita es mañana')
            ->view('emails.citas.reminder')
            ->attachData($ics, "cita-{$this->cita->id}.ics", ['mime' => 'text/calendar']);
    }
}
