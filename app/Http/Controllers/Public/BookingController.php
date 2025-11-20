<?php


namespace App\Http\Controllers\Public;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitaRequest;
use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Client;
use App\Models\Especialista;
use App\Models\MetaTags;
use App\Models\Servicio;
use App\Models\TenantInfo;
use App\Services\PricingService;
use App\Services\AvailabilityService;
use App\Services\TenantMailService;
use App\Support\TenantSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;


class BookingController extends Controller
{

    public function showForm(Barbero $barbero, Request $request)
    {
        $tenantinfo = TenantInfo::first();
        $tags = MetaTags::where('section', 'Blog')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $barbero->nombre);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        // Servicios activos con precio/duración efectivo (pivot o base)
        $servicios = $barbero->servicios()
            ->wherePivot('activo', true)
            ->where('servicios.activo', true)
            ->orderBy('servicios.nombre')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'nombre' => $s->nombre,
                    'price_cents' => $s->pivot->price_cents ?? $s->base_price_cents,
                    'duration_minutes' => $s->pivot->duration_minutes ?? $s->duration_minutes,
                ];
            });

        return view('frontend.barber.form', compact('barbero', 'servicios'));
    }

    public function servicios(Barbero $barbero)
    {
        $servicios = $barbero->servicios()
            ->wherePivot('activo', true)
            ->where('servicios.activo', true)
            ->get()
            ->map(function ($srv) use ($barbero) {
                $price = $srv->pivot->price_cents ?? $srv->base_price_cents;
                $dur = $srv->pivot->duration_minutes ?? $srv->duration_minutes;
                return [
                    'id' => $srv->id,
                    'nombre' => $srv->nombre,
                    'descripcion' => $srv->descripcion,
                    'price_cents' => $price,
                    'duration_minutes' => $dur,
                ];
            });


        return response()->json($servicios);
    }


    public function disponibilidad(Barbero $barbero, Request $request, AvailabilityService $availability)
    {
        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->toDateString()],
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => ['integer', 'exists:servicios,id'],
        ]);

        // Determinar duración total (suma de seleccionados) en minutos
        $duracion = 0;
        $servs = Servicio::whereIn('id', $data['servicios'])->get();
        foreach ($servs as $srv) {
            $pivot = $barbero->servicios()->where('servicio_id', $srv->id)->first()?->pivot;
            $dur = $pivot && $pivot->duration_minutes ? $pivot->duration_minutes : $srv->duration_minutes;
            $duracion += (int)$dur;
        }

        $slots = $availability->availableSlots($barbero, $data['date'], $duracion);

        return response()->json(['slots' => $slots]); // e.g. ["09:00","09:30",...]
    }

    public function cotizar(Request $request, Barbero $barbero, PricingService $pricing)
    {
        $request->validate([
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => ['integer', 'exists:servicios,id'],
        ]);


        [$total, $detalle] = $pricing->quoteFor($barbero, $request->input('servicios'));


        return response()->json([
            'total_cents' => $total,
            'detalle' => $detalle,
        ]);
    }


    public function reservar(Request $request, PricingService $pricing, AvailabilityService $availability,  TenantMailService $tenantMailService)
    {
        $data = $request->validate([
            'barbero_id' => ['required', 'integer', 'exists:barberos,id'],
            'cliente_nombre' => ['required', 'string', 'max:120'],
            'cliente_email' => ['required', 'email', 'max:120'],
            'cliente_telefono' => ['nullable', 'string', 'max:50'],
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => ['integer', 'exists:servicios,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->toDateString()],
            'time' => ['required', 'string'],
        ]);
        $mailer = $tenantMailService->forCurrentTenant();
        $barbero = Barbero::findOrFail($data['barbero_id']);

        $timeInput = trim($data['time']);
        $parsedTime = $this->parseTimeFlexible($timeInput);
        if (!$parsedTime) {
            return back()->withErrors(['time' => 'La hora no tiene un formato válido.'])
                ->withInput();
        }
        $time24 = $parsedTime->format('H:i');

        // Cotizar total y construir snapshot
        [$totalCents, $detalle] = $pricing->quoteFor($barbero, $data['servicios']); // devuelve total + [servicio_id, price_cents, duration_minutes]...

        // Duración total
        $durTotal = collect($detalle)->sum('duration_minutes');

        // Validar que el slot esté libre todavía
        $startsAt = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $time24);
        $endsAt   = $startsAt->copy()->addMinutes($durTotal);

        // Revalidar disponibilidad atómica antes de insertar (evita doble booking)
        $slots = $availability->availableSlots($barbero, $data['date'], $durTotal); // ['9:00 AM', ...]
        $startsAt12 = $startsAt->format('g:i A'); // '5:45 PM'
        if (!in_array($startsAt12, $slots, true)) {
            return back()->withErrors('Ese horario acaba de ocuparse. Elige otro.')->withInput();
        }
        // Crear cita y snapshot de servicios
        DB::transaction(function () use ($barbero, $data, $totalCents, $detalle, $startsAt, $endsAt, $request, $mailer) {

            $email  = trim((string)$request->input('cliente_email'));
            $nombre = trim((string)$request->input('cliente_nombre'));
            $tel    = trim((string)$request->input('cliente_telefono'));
            $client = Client::where('email', $email)->first();
            if (isset($client) && $client->discount > 0 && $client->discount != null)
                $totalCents = $client->discount * 100;
            $client = null;
            if ($email !== '') {
                $client = Client::firstOrCreate(['email' => $email], [
                    'nombre' => $nombre ?: null,
                    'telefono' => $tel ?: null,
                    'last_seen_at' => now(),
                ]);
                $client->fill([
                    'nombre' => $nombre ?: $client->nombre,
                    'telefono' => $tel ?: $client->telefono,
                    'last_seen_at' => now(),
                ])->save();
            }
            $lastCita = Cita::where('client_id', $client?->id)
                ->orderByDesc('ends_at')
                ->first();

            if ($lastCita && $lastCita->status === 'not_arrive') {
                $totalCents = $totalCents + ($client->due_price * 100);
            }

            $existCita = Cita::where('client_id', $client?->id)
                ->where('starts_at', $startsAt)
                ->where('status', 'confirmed')
                ->exists();
            if ($existCita) {
                return back()->withErrors('Ya existe una cita agendada para tí en el horario indicado.')->withInput();
            }

            $cita = Cita::create([
                'barbero_id' => $barbero->id,
                'user_id' => auth()->id(),
                'client_id'      => $client?->id,
                'cliente_nombre' => $data['cliente_nombre'],
                'cliente_email' => $data['cliente_email'] ?? null,
                'cliente_telefono' => $data['cliente_telefono'] ?? null,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'total_cents' => $totalCents,
                'status' => 'confirmed',
                'notas' => null,
                'source' => 'landing',
            ]);

            $tenantId = TenantInfo::first()->tenant;
            $adminShowUrl = route('citas.mine');

            // Aquí metemos nuestro push a todos los admins/barberos logueados de este tenant:
            \App\Services\AppointmentNotifier::nuevaCitaParaTenant($tenantId, [
                'title' => 'Nueva cita reservada',
                'body'  => $cita->cliente_nombre . ' ' . $cita->starts_at->format('d/m/Y H:i') . ' con ' . $cita->barbero->nombre,
                'cita_id' => $cita->id,
                'url'   => $adminShowUrl,
            ]);

            $syncData = [];

            foreach ($detalle as $d) {
                $syncData[$d['servicio_id']] = [
                    'price_cents'       => $d['price_cents'],
                    'duration_minutes'  => $d['duration_minutes'],
                ];
            }
            // attach (siempre agrega) o syncWithoutDetaching (no quita existentes)
            $cita->servicios()->attach($syncData);

            $serviciosResumen = [];

            foreach ($syncData as $servicioId => $pivot) {
                $srv = \App\Models\Servicio::find($servicioId);
                if (!$srv) continue;

                $serviciosResumen[] = [
                    'nombre'    => $srv->nombre,
                    'precio'    => (int)($pivot['price_cents'] / 100),
                    'duracion'  => $pivot['duration_minutes'],
                ];
            }

            $tz = config('app.timezone', 'America/Costa_Rica');
            $fechaHuman  = $cita->starts_at->timezone($tz)->isoFormat('dddd D [de] MMMM YYYY');
            $horaHuman   = $cita->starts_at->timezone($tz)->format('H:i');
            $duracionMin = $cita->ends_at->diffInMinutes($cita->starts_at);
            $servicios   = $serviciosResumen;  // o arma el texto según lo que guardes
            $totalCols   = (int) ($cita->total_cents / 100);

            // link a detalle admin (ajusta nombre de ruta según lo tengas)
            $adminShowUrl = route('citas.show', $cita->id);
            // destinatario del barbero
            $barberoEmail = $cita->barbero->email ?? optional($cita->barbero->user)->email;

            if ($barberoEmail) {
                $viewData = [
                    'barberoNombre'  => $cita->barbero->nombre,
                    'clienteNombre'  => $cita->cliente_nombre,
                    'clienteEmail'   => $cita->cliente_email,
                    'clientePhone'   => $cita->cliente_telefono,
                    'fechaHuman'     => $fechaHuman,
                    'horaHuman'      => $horaHuman,
                    'duracionMin'    => $duracionMin,
                    'serviciosResumen' => $servicios,
                    'totalColones'   => $totalCols,
                    'adminShowUrl'   => $adminShowUrl,
                ];

                // Si prefieres encolar, puedes usar un Mailable. Como pediste Mail::send, lo dejo así:
                /* Mail::send(
                    ['html' => 'emails.citas.new_for_barber'],
                    $viewData,
                    function ($m) use ($barberoEmail, $cita) {
                        $m->to($barberoEmail)
                            ->from(env('MAIL_FROM_ADDRESS'), 'Info Barbería') // 👈 aquí cambias el nombre visible
                            ->subject('📅 Nueva cita agendada — #' . $cita->id);
                    }
                ); */
                $mailer->send(
                    ['html' => 'emails.citas.new_for_barber'],
                    $viewData,
                    function ($m) use ($barberoEmail, $cita) {
                        $m->to($barberoEmail)
                            // from ya viene por config('mail.from.*'),
                            // solo lo pones aquí si quieres sobreescribirlo:
                            // ->from($settings->from_address, $settings->from_name)
                            ->subject('📅 Nueva cita agendada — #' . $cita->id);
                    }
                );

                //Enviar correo al cliente para que pueda eliminar, cancelar la cita
                $tenantId = TenantInfo::first()->tenant;
                $tenant = TenantSettings::get($tenantId);
                $domain = $tenantId == "muebleriasarchi" || $tenantId == "avelectromecanica" ? "https://{$tenantId}.com" : "https://{$tenantId}.safeworsolutions.com";

                $appTz = config('app.timezone', 'America/Costa_Rica');

                // Aseguramos que la fecha de inicio esté en el timezone de la app para el cálculo
                $startsAtTz = $startsAt->copy()->timezone($appTz);

                // Vence 1 horas antes de la cita
                $expiresAt = $startsAtTz->copy()->subHours(1);

                // Si por alguna razón la cita es en <= 2 horas, el link vencería en el pasado;
                // en ese caso, forzamos un mínimo de 1 minuto para evitar una firma ya expirada.
                if ($expiresAt->lessThanOrEqualTo(now($appTz))) {
                    $expiresAt = now($appTz)->addMinute();
                }

                URL::forceRootUrl($domain);
                URL::forceScheme('https');
                $acceptUrl  = URL::temporarySignedRoute('auto.accept',  $expiresAt, ['cita' => $cita->id]);
                $reschedUrl = URL::temporarySignedRoute('auto.resched', $expiresAt, ['cita' => $cita->id]);
                $declineUrl = URL::temporarySignedRoute('auto.decline', $expiresAt, ['cita' => $cita->id]);

                // Restaurar configuración de URL para el resto de la app
                URL::forceRootUrl(config('app.url'));
                URL::forceScheme(null);
                // Datos para el blade del correo (ya lo tienes: auto_proposed)
                $tz = config('app.timezone', 'America/Costa_Rica');
                $viewData = [
                    'clienteNombre' => $cita->cliente_nombre,
                    'barberoNombre' => $cita->barbero->nombre,
                    'fechaHuman'     => $fechaHuman,
                    'horaHuman'      => $horaHuman,
                    'duracionMin'    => $duracionMin,
                    'serviciosResumen' => $servicios,
                    'totalColones'  => $totalCols,
                    'acceptUrl'     => $acceptUrl,
                    'reschedUrl'    => $reschedUrl,
                    'declineUrl'    => $declineUrl,
                    'cancelHours'   => optional($tenant)->cancel_window_hours,
                    'reschedHours'  => optional($tenant)->reschedule_window_hours,
                ];

                // Enviar con tu estilo Mail::send
                $mailer->send(
                    [
                        'html' => 'emails.auto_proposed',
                        'text' => 'emails.auto_proposed_text'
                    ],
                    $viewData,
                    function ($m) use ($cita, $barbero, $startsAt) {
                        $m->to($cita->cliente_email)
                            ->subject(
                                'Cita confirmada con ' . $barbero->nombre . ' — ' . $startsAt->format('d/m/Y')
                            );
                    }
                );
            }
        });

        return redirect()->back()->with('ok', '¡Cita reservada! Gracias por confiar en nuestros barberos.');
    }

    private function parseTimeFlexible(string $value): ?\Carbon\Carbon
    {
        $value = trim($value);
        $candidates = [
            'g:i A',
            'g:iA',
            'h:i A',
            'h:iA', // 12h variantes con/ sin espacio
            'G:i',
            'H:i',                    // 24h
        ];

        foreach ($candidates as $fmt) {
            try {
                $t = \Carbon\Carbon::createFromFormat($fmt, $value);
                if ($t !== false) {
                    // normalizamos a hoy, pero sólo usaremos la parte de la hora
                    return \Carbon\Carbon::today()->setTimeFromTimeString($t->format('H:i'));
                }
            } catch (\Throwable $e) {
                // continúa probando
            }
        }
        // Extra: permitir '5 PM' sin minutos -> asumir ':00'
        if (preg_match('/^\s*(\d{1,2})\s*([AP]M)\s*$/i', $value, $m)) {
            $fix = sprintf('%d:00 %s', (int)$m[1], strtoupper($m[2]));
            try {
                $t = \Carbon\Carbon::createFromFormat('g:i A', $fix);
                return \Carbon\Carbon::today()->setTimeFromTimeString($t->format('H:i'));
            } catch (\Throwable $e) {
            }
        }

        return null;
    }
}
