<?php

namespace App\Jobs;

use App\Models\{Client, Cita};
use App\Services\AutoSchedulerService;
use App\Support\TenantSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ProposeAutoAppointmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $clientId;
    public function __construct(int $clientId)
    {
        $this->clientId = $clientId;
    }

    public function handle(AutoSchedulerService $svc): void
    {
        $client = Client::find($this->clientId);
        if (!$client || !$client->auto_book_opt_in) return;
        $tenantId = tenant('id') ?? config('app.name'); // ajusta según tu tenancy
        $tenant = TenantSettings::get($tenantId);
        $best = $svc->findBestSlotFor($client);
        if (!$best) return;

        $barbero = $best['barbero'];
        $startLocal = $best['start'];
        $endLocal   = $best['end'];

        // Evitar duplicados: ¿ya existe una propuesta auto en ese día/franja?
        $dup = Cita::where('client_id', $client->id)
            ->where('barbero_id', $barbero->id)
            ->where('status', 'confirmed')
            ->where('is_auto', true)
            ->whereDate('starts_at', $startLocal->toDateString())
            ->exists();
        if ($dup) return;

        // Crear cita tentativa (guarda en TZ local o UTC según tu convención)
        $holdHours = (int)($tenant->auto_book_confirm_hold_hours ?? 36);
        $cita = Cita::create([
            'client_id'     => $client->id,
            'barbero_id'    => $barbero->id,
            'status'        => 'confirmed',
            'is_auto'       => true,
            'hold_expires_at' => now()->addHours($holdHours),
            'starts_at'     => $startLocal, // si guardas en UTC: ->copy()->timezone('UTC')
            'ends_at'       => $endLocal,
            'cliente_nombre' => $client->nombre,
            'cliente_email'  => $client->email,
            'cliente_phone'  => $client->telefono,
            'resumen_servicios' => 'Propuesta automática', // ajusta si infieres servicios
            'total_cents'   => 0, // opcional si estimas costo
        ]);
        $domain = $tenantId == "muebleriasarchi" || $tenantId == "avelectromecanica" ? "https://{$tenantId}.com" : "https://{$tenantId}.safeworsolutions.com";
        URL::forceRootUrl($domain);
        URL::forceScheme('https');
        // Links firmados
        $acceptUrl  = URL::temporarySignedRoute('auto.accept',  now()->addHours(36), ['cita' => $cita->id]);
        $reschedUrl = URL::temporarySignedRoute('auto.resched', now()->addHours(36), ['cita' => $cita->id]);
        $declineUrl = URL::temporarySignedRoute('auto.decline', now()->addHours(36), ['cita' => $cita->id]);
        URL::forceRootUrl(config('app.url'));
        URL::forceScheme(null);
        // Datos para el blade del correo (ya lo tienes: auto_proposed)
        $tz = config('app.timezone', 'America/Costa_Rica');
        $viewData = [
            'clienteNombre' => $client->nombre ?: 'Cliente',
            'barberoNombre' => $barbero->nombre,
            'fechaHuman'    => $startLocal->timezone($tz)->isoFormat('dddd D [de] MMMM YYYY'),
            'horaHuman'     => $startLocal->timezone($tz)->format('H:i'),
            'duracionMin'   => $endLocal->diffInMinutes($startLocal),
            'serviciosResumen' => $cita->resumen_servicios,
            'totalColones'  => (int)($cita->total_cents / 100),
            'acceptUrl'     => $acceptUrl,
            'reschedUrl'    => $reschedUrl,
            'declineUrl'    => $declineUrl,
            'cancelHours'   => optional($tenant)->cancel_window_hours,
            'reschedHours'  => optional($tenant)->reschedule_window_hours,
        ];

        // Enviar con tu estilo Mail::send
        Mail::send(
            ['html' => 'emails.auto_proposed', 'text' => 'emails.auto_proposed_text'],
            $viewData,
            function ($m) use ($client, $barbero, $startLocal) {
                $m->to($client->email)
                    ->from(
                        env('MAIL_FROM_ADDRESS'),   // usa MAIL_FROM_ADDRESS del .env
                        'Info Barbería'       // usa MAIL_FROM_NAME del .env
                    )
                    ->subject('Propuesta de cita con ' . $barbero->nombre . ' — ' . $startLocal->format('d/m/Y'));
            }
        );
        // marcar para no repetir
        $cadence = $client->effective_cadence_days ?? ($tenant->auto_book_default_cadence_days ?? 7);
        // opcional: anclar al mismo weekday/hora de la cita propuesta
        $next = $startLocal->copy()->addDays($cadence);
        $next = $next->setTimeFromTimeString($client->preferred_start?->format('H:i') ?? $startLocal->format('H:i'));
        $client->update([
            'last_auto_booked_at' => now(),
            'next_due_at' => $next->timezone('UTC'),
        ]);
    }
}
