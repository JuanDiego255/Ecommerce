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

        $tenantId = tenant('id') ?? config('app.name');
        $tenant   = TenantSettings::get($tenantId);

        // ── Guarda de doble-disparo ──────────────────────────────────────────────
        // Si ya existe una cita auto futura confirmada (puede ocurrir si el comando
        // fue ejecutado más de una vez antes de que el job actualizara next_due_at),
        // sincronizamos next_due_at con esa cita y salimos sin duplicar.
        $existingFuture = Cita::where('client_id', $client->id)
            ->where('is_auto', true)
            ->where('status', 'confirmed')
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->first();

        if ($existingFuture) {
            $client->update(['next_due_at' => $existingFuture->starts_at]);
            return;
        }

        // ── Buscar el siguiente hueco usando findInUpdate (frecuencia fija) ──────
        // activeInUpdate=true → findInUpdate: busca el próximo día preferido después
        // de la última cita que ya ocurrió (confirmed/completed con starts_at ≤ now).
        $best = $svc->findBestSlotFor($client, true);
        if (!$best) return;

        $barbero    = $best['barbero'];
        $startLocal = $best['start'];
        $endLocal   = $best['end'];

        // Guarda adicional: no crear si ya hay cita auto ese mismo día con ese barbero
        $dup = Cita::where('client_id', $client->id)
            ->where('barbero_id', $barbero->id)
            ->where('is_auto', true)
            ->where('status', 'confirmed')
            ->whereDate('starts_at', $startLocal->toDateString())
            ->exists();
        if ($dup) return;

        // ── Crear la cita auto-agendada ─────────────────────────────────────────
        $holdHours = (int)($tenant->auto_book_confirm_hold_hours ?? 36);

        $cita = Cita::create([
            'client_id'         => $client->id,
            'barbero_id'        => $barbero->id,
            'status'            => 'confirmed',
            'is_auto'           => true,
            'hold_expires_at'   => now()->addHours($holdHours),
            'starts_at'         => $startLocal,
            'ends_at'           => $endLocal,
            'cliente_nombre'    => $client->nombre,
            'cliente_email'     => $client->email,
            'cliente_phone'     => $client->telefono,
            'resumen_servicios' => 'Propuesta automática',
            'total_cents'       => 0,
        ]);

        // ── Generar links firmados para el correo ───────────────────────────────
        $domain = in_array($tenantId, ['muebleriasarchi', 'avelectromecanica'])
            ? "https://{$tenantId}.com"
            : "https://{$tenantId}.safeworsolutions.com";

        URL::forceRootUrl($domain);
        URL::forceScheme('https');

        $linkExpiry = now()->addHours(max($holdHours, 36));
        $acceptUrl  = URL::temporarySignedRoute('auto.accept',  $linkExpiry, ['cita' => $cita->id]);
        $reschedUrl = URL::temporarySignedRoute('auto.resched', $linkExpiry, ['cita' => $cita->id]);
        $declineUrl = URL::temporarySignedRoute('auto.decline', $linkExpiry, ['cita' => $cita->id]);

        URL::forceRootUrl(config('app.url'));
        URL::forceScheme(null);

        // ── Enviar correo de propuesta al cliente ───────────────────────────────
        $tz = config('app.timezone', 'America/Costa_Rica');

        $viewData = [
            'clienteNombre'    => $client->nombre ?: 'Cliente',
            'barberoNombre'    => $barbero->nombre,
            'fechaHuman'       => $startLocal->timezone($tz)->isoFormat('dddd D [de] MMMM YYYY'),
            'horaHuman'        => $startLocal->timezone($tz)->format('H:i'),
            'duracionMin'      => $endLocal->diffInMinutes($startLocal),
            'serviciosResumen' => $cita->resumen_servicios,
            'totalColones'     => (int)($cita->total_cents / 100),
            'acceptUrl'        => $acceptUrl,
            'reschedUrl'       => $reschedUrl,
            'declineUrl'       => $declineUrl,
            'cancelHours'      => optional($tenant)->cancel_window_hours,
            'reschedHours'     => optional($tenant)->reschedule_window_hours,
        ];

        Mail::send(
            ['html' => 'emails.auto_proposed', 'text' => 'emails.auto_proposed_text'],
            $viewData,
            function ($m) use ($client, $barbero, $startLocal) {
                $m->to($client->email)
                  ->from(env('MAIL_FROM_ADDRESS'), 'Info Barbería')
                  ->subject('Cita agendada con ' . $barbero->nombre . ' — ' . $startLocal->format('d/m/Y'));
            }
        );

        // ── Actualizar tracking del cliente ─────────────────────────────────────
        // next_due_at = starts_at de la cita recién creada.
        // El scheduler volverá a disparar exactamente cuando llegue ese momento,
        // cerrando el ciclo semanal/quincenal automáticamente.
        $client->update([
            'last_auto_booked_at' => now(),
            'next_due_at'         => $startLocal->copy()->timezone('UTC'),
        ]);
    }
}
