<?php

// app/Observers/CitaObserver.php
namespace App\Observers;

use App\Mail\AppointmentApproved;
use App\Mail\AppointmentCancelled;
use App\Services\AutoSchedulerService;
use App\Models\Cita;
use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\TenantSetting;
use App\Support\TenantSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

class CitaObserver
{
    public function updated(Cita $cita): void
    {
        $svc = new AutoSchedulerService;
        if (!$cita->wasChanged('status')) return;
        $tz = config('app.timezone', 'America/Costa_Rica');
        $old = $cita->getOriginal('status');
        $new = $cita->status;
        $tenantId = tenant('id') ?? config('app.name'); // ajusta según tu tenancy
        $tenant = TenantSettings::get($tenantId);
        $client = $cita->client;
        // Aprobada (confirmada)
        if ($old !== 'confirmed' && $new === 'confirmed' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentApproved($cita));
        }

        // Cancelada
        if ($old !== 'cancelled' && $new === 'cancelled' && $cita->cliente_email) {
            Mail::to($cita->cliente_email)->queue(new AppointmentCancelled($cita));
        }

        if ($cita->wasChanged('status') && $cita->status === 'completed' && $cita->client_id) {

            if ($client && $client->auto_book_opt_in) {
                //Logica para proponer nueva cita
                $best = $svc->findBestSlotFor($client, true);
                $this->createCita($best, $client, $tenant, $tenantId);
                //Logica para proponer nueva cita
            }
        }
        if ($cita->wasChanged('status') && $cita->status === 'not_arrive' && $cita->client_id) {
            $tenantId = TenantInfo::first()->tenant;
            $settings_barber = TenantSetting::where('tenant_id', $tenantId)->first();
            $total_cents = $cita->total_cents / 100;
            $porc = (int) round(($settings_barber->no_show_fee_cents ?? 0) / 100);
            $due = $total_cents * ($porc / 100);
            $client->update([
                'due_price' => $due
            ]);
        }
    }

    public function createCita($best, $client, $tenant, $tenantId)
    {
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
        $preferredStart = $client->preferred_start
            ? Carbon::parse($client->preferred_start)
            : null;

        $next = $next->setTimeFromTimeString(
            $preferredStart?->format('H:i') ?? $startLocal->format('H:i')
        );
        $client->update([
            'last_auto_booked_at' => now(),
            'next_due_at' => $next->timezone('UTC'),
        ]);
    }
}
