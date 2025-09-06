<?php


namespace App\Http\Controllers\Public;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitaRequest;
use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Especialista;
use App\Models\Servicio;
use App\Services\PricingService;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BookingController extends Controller
{

    public function showForm(Barbero $barbero, Request $request)
    {
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


    public function reservar(Request $request, PricingService $pricing, AvailabilityService $availability)
    {
        $data = $request->validate([
            'barbero_id' => ['required', 'integer', 'exists:barberos,id'],
            'cliente_nombre' => ['required', 'string', 'max:120'],
            'cliente_email' => ['nullable', 'email', 'max:120'],
            'cliente_telefono' => ['nullable', 'string', 'max:50'],
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => ['integer', 'exists:servicios,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->toDateString()],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $barbero = Barbero::findOrFail($data['barbero_id']);

        // Cotizar total y construir snapshot
        [$totalCents, $detalle] = $pricing->quoteFor($barbero, $data['servicios']); // devuelve total + [servicio_id, price_cents, duration_minutes]...

        // Duración total
        $durTotal = collect($detalle)->sum('duration_minutes');

        // Validar que el slot esté libre todavía
        $startsAt = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['time']);
        $endsAt   = $startsAt->copy()->addMinutes($durTotal);

        // Revalidar disponibilidad atómica antes de insertar (evita doble booking)
        $slots = $availability->availableSlots($barbero, $data['date'], $durTotal);
        if (!in_array($startsAt->format('H:i'), $slots)) {
            return back()->withErrors('Ese horario acaba de ocuparse. Elige otro.')->withInput();
        }

        // Crear cita y snapshot de servicios
        DB::transaction(function () use ($barbero, $data, $totalCents, $detalle, $startsAt, $endsAt) {
            $cita = Cita::create([
                'barbero_id' => $barbero->id,
                'user_id' => auth()->id(),
                'cliente_nombre' => $data['cliente_nombre'],
                'cliente_email' => $data['cliente_email'] ?? null,
                'cliente_telefono' => $data['cliente_telefono'] ?? null,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'total_cents' => $totalCents,
                'status' => 'pending',
                'notas' => null,
                'source' => 'landing',
            ]);

            foreach ($detalle as $d) {
                $cita->servicios()->attach($d['servicio_id'], [
                    'price_cents' => $d['price_cents'],
                    'duration_minutes' => $d['duration_minutes'],
                ]);
            }
        });

        return redirect()->back()->with('ok', '¡Cita reservada! Te contactaremos para confirmar.');
    }
}
