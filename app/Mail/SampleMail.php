<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SampleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        $tenantId    = tenant('id') ?? \App\Models\TenantInfo::first()->tenant;
        $emailConfig = \App\Models\CompanyEmailSetting::where('tenant_id', $tenantId)->first();

        if ($emailConfig) {
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
                'mail.from.name'    => $emailConfig->from_name ?? config('app.name'),
            ]);
            $this->mailer('dynamic');
            $fromAddress = $emailConfig->from_address;
            $fromName    = $emailConfig->from_name ?? config('app.name');
        } else {
            $fromAddress = config('mail.from.address');
            $fromName    = config('mail.from.name', config('app.name'));
        }

        return $this->from($fromAddress, $fromName)
            ->subject('Correo de Prueba')
            ->view('emails.sample');
    }
}
