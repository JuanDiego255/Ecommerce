<?php

// app/Mail/AppointmentApproved.php
namespace App\Mail;

use App\Models\Cita;
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

        // Fechas en hora local para el .ics y para la vista
        $startLocal = $this->cita->starts_at->timezone($tz)->format('Y-m-d\TH:i:s');
        $endLocal   = $this->cita->ends_at->timezone($tz)->format('Y-m-d\TH:i:s');

        // Links firmados (públicos)
        $cancelUrl  = URL::signedRoute('booking.cancel', ['cita' => $this->cita->id]);
        $reschedUrl = URL::signedRoute('booking.reschedule', ['cita' => $this->cita->id]);
        $tenantId = tenant('id') ?? config('app.name');
        $set = TenantSettings::get($tenantId);
        // Archivo .ics
        $ics = IcsBuilder::appointment(
            "cita-{$this->cita->id}@barberia",
            'Cita confirmada',
            "Barbero: {$tenantId}\nServicios: {$this->cita->resumen_servicios}",
            $startLocal,
            $endLocal,
            $tz
        );
        $noShowFeeColones = (int) round(($set->no_show_fee_cents ?? 0) / 100);

        // Datos para la vista Blade
        $viewData = [
            'nombre'     => $tenantId,
            'barbero'    => $this->cita->barbero->nombre,
            'fecha'      => $this->cita->starts_at->timezone($tz)->format('d/m/Y H:i'),
            'servicios'  => $this->cita->resumen_servicios,
            'cancelUrl'  => $cancelUrl,
            'reschedUrl' => $reschedUrl,
            'monto' =>  $noShowFeeColones,
            'cancelText' => "Puedes cancelar hasta {$set->cancel_window_hours} h antes.",
            'reschText'  => "Puedes reprogramar hasta {$set->reschedule_window_hours} h antes.",
        ];

        return $this->from(
            env('MAIL_FROM_ADDRESS'),   // 👈 MAIL_FROM_ADDRESS
            'Info Babería'       // 👈 MAIL_FROM_NAME
        )
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
