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
        // 1) Obtener tenant actual (según tu sistema)
        $tenantId = tenant('id') ?? \App\Models\TenantInfo::first()->tenant;

        // 2) Obtener configuración SMTP del tenant
        $emailConfig = \App\Models\CompanyEmailSetting::where('tenant_id', $tenantId)->first();

        if ($emailConfig) {

            // 3) Configurar mailer dinámico
            config([
                'mail.mailers.dynamic' => [
                    'transport'  => $emailConfig->mailer ?? 'smtp',
                    'host'       => $emailConfig->host,
                    'port'       => $emailConfig->port,
                    'encryption' => $emailConfig->encryption,
                    'username'   => $emailConfig->username,
                    'password'   => $emailConfig->password, // se desencripta por el accessor
                    'timeout'    => null,
                    'auth_mode'  => null,
                ],
                'mail.from.address' => $emailConfig->from_address,
                'mail.from.name'    => $emailConfig->from_name ?? 'Info Barbería',
            ]);

            // 4) Indicar que este Mailable use el mailer dinámico
            $this->mailer('dynamic');

            $fromAddress = $emailConfig->from_address;
            $fromName    = $emailConfig->from_name ?? 'Info Barbería';
        } else {
            // Fallback al MAIL_FROM del .env si no existe configuración del tenant
            $fromAddress = config('mail.from.address');
            $fromName    = config('mail.from.name', 'Info Barbería');
        }

        // 5) Construir el correo normalmente
        return $this->from($fromAddress, $fromName)
            ->subject('❌ Tu cita fue cancelada')
            ->view('emails.citas.cancelled');
    }
}
