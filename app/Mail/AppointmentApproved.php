<?php

// app/Mail/AppointmentApproved.php
namespace App\Mail;

use App\Models\Cita;
use App\Models\CompanyEmailSetting;
use App\Models\TenantInfo;
use App\Models\TenantSetting;
use App\Support\IcsBuilder;
use App\Support\TenantSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AppointmentApproved extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public Cita $cita) {}

    public function build()
    {
        $tz = config('app.timezone', 'America/Costa_Rica');

        $startLocal = $this->cita->starts_at->timezone($tz)->format('Y-m-d\TH:i:s');
        $endLocal   = $this->cita->ends_at->timezone($tz)->format('Y-m-d\TH:i:s');

        $cancelUrl  = URL::signedRoute('booking.cancel', ['cita' => $this->cita->id]);
        $reschedUrl = URL::signedRoute('booking.reschedule', ['cita' => $this->cita->id]);

        $tenantId = tenant('id') ?? config('app.name');
        $set = TenantSettings::get($tenantId);

        $ics = IcsBuilder::appointment(
            "cita-{$this->cita->id}@barberia",
            'Cita confirmada',
            "Barbero: {$tenantId}\nServicios: {$this->cita->resumen_servicios}",
            $startLocal,
            $endLocal,
            $tz
        );

        $noShowFeeColones = (int) round(($set->no_show_fee_cents ?? 0) / 100);

        $viewData = [
            'nombre'     => $tenantId,
            'barbero'    => $this->cita->barbero->nombre,
            'fecha'      => $this->cita->starts_at->timezone($tz)->format('d/m/Y H:i'),
            'servicios'  => $this->cita->resumen_servicios,
            'cancelUrl'  => $cancelUrl,
            'reschedUrl' => $reschedUrl,
            'monto'      => $noShowFeeColones,
            'cancelText' => "Puedes cancelar hasta {$set->cancel_window_hours} h antes.",
            'reschText'  => "Puedes reprogramar hasta {$set->reschedule_window_hours} h antes.",
        ];

        // 🔹 1) Obtener config de correo del tenant
        $emailConfig = CompanyEmailSetting::where('tenant_id', $tenantId)->first();

        if ($emailConfig) {
            // 🔹 2) Configurar mailer dinámico para ESTE envío
            config([
                'mail.mailers.dynamic' => [
                    'transport'  => $emailConfig->mailer ?? 'smtp',
                    'host'       => $emailConfig->host,
                    'port'       => $emailConfig->port,
                    'encryption' => $emailConfig->encryption,
                    'username'   => $emailConfig->username,
                    'password'   => $emailConfig->password, // ya desencriptado por el accessor
                    'timeout'    => null,
                    'auth_mode'  => null,
                ],
                'mail.from.address' => $emailConfig->from_address,
                'mail.from.name'    => $emailConfig->from_name ?? 'Info Barbería',
            ]);

            // 🔹 3) Indicar que este mailable use el mailer 'dynamic'
            $this->mailer('dynamic');

            $fromAddress = $emailConfig->from_address;
            $fromName    = $emailConfig->from_name ?? 'Info Barbería';
        } else {
            // Fallback al .env/config si no hay configuración del tenant
            $fromAddress = config('mail.from.address');
            $fromName    = config('mail.from.name', 'Info Barbería');
        }

        return $this
            ->from($fromAddress, $fromName)
            ->subject('✅ Tu cita fue confirmada')
            ->view('emails.citas.approved')
            ->with($viewData)
            ->attachData(
                $ics,
                "cita-{$this->cita->id}.ics",
                ['mime' => 'text/calendar']
            );
    }
}
