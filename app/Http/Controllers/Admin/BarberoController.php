<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barbero;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BarberoController extends Controller
{
    public function index(Request $request)
    {
        $barberos = Barbero::when($request->filled('q'), fn($q) => $q->where('nombre', 'like', '%' . $request->q . '%'))
            ->orderBy('nombre')->get();
        return view('admin.barberos.index', compact('barberos'));
    }

    public function show(Request $request, Barbero $barbero)
    {
        // Tab activo desde query (?tab=servicios) o fallback
        $tab = $request->query('tab', 'info');
        $valid = ['info', 'servicios', 'agenda', 'calendario', 'galeria', 'stats'];
        $fotos = $barbero->trabajos()->latest()->get();
        if (!in_array($tab, $valid)) $tab = 'info';

        // “volver” controlado: si vienes del calendario o del listado:
        $back = $request->query('back', url('/barberos')); // puedes setear back=? al link de origen

        // Carga de datos mínimos para cabecera (las vistas internas pueden cargar lo suyo)
        $barbero->loadCount(['citas']); // ejemplo
        $allServicios = Servicio::where('activo', true)->orderBy('nombre')->get();
        $workDays   = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];
        $slot       = (int)($barbero->slot_minutes ?? 30);
        $workStart  = substr($barbero->work_start ?? '09:00', 0, 5);
        $workEnd    = substr($barbero->work_end   ?? '18:00', 0, 5);
        //stats
        $tz = config('app.timezone', 'America/Costa_Rica');
        $start = $request->filled('start') ? \Carbon\Carbon::parse($request->input('start'), $tz)->startOfDay() : now($tz)->subDays(30)->startOfDay();
        $end   = $request->filled('end')   ? \Carbon\Carbon::parse($request->input('end'),   $tz)->endOfDay() : now($tz)->endOfDay();

        $startUtc = $start->clone()->timezone('UTC');
        $endUtc   = $end->clone()->timezone('UTC');
        $stats = null;
        $porStatusBarbero = null;
        $porDiaBarbero = null;
        if ($tab === 'stats') {
            $q = \App\Models\Cita::where('barbero_id', $barbero->id)
                ->whereBetween('starts_at', [$startUtc, $endUtc]);

            $stats = [
                'total'       => (clone $q)->count(),
                'pending'     => (clone $q)->where('status', 'pending')->count(),
                'confirmed'   => (clone $q)->where('status', 'confirmed')->count(),
                'completed'   => (clone $q)->where('status', 'completed')->count(),
                'cancelled'   => (clone $q)->where('status', 'cancelled')->count(),
                'ingresos'    => (clone $q)->whereIn('status', ['confirmed', 'completed'])->sum('total_cents'),
            ];
            $porDiaBarbero = (clone $q)
                ->select(DB::raw("DATE(CONVERT_TZ(starts_at, '+00:00', '" . now()->format('P') . "')) as d"), DB::raw('COUNT(*) as qty'))
                ->groupBy('d')
                ->orderBy('d')
                ->get();

            // por status
            $porStatusBarbero = (clone $q)
                ->select('status', DB::raw('COUNT(*) as qty'))
                ->groupBy('status')
                ->pluck('qty', 'status');
            // (Opcional) top servicios del barbero en el rango (si guardas pivot/relación de servicios en Cita)
            // $topServicios = ...
        }
        return view('admin.barberos.profile', compact('barbero', 'tab', 'back', 'allServicios', 'workDays', 'slot', 'workStart', 'workEnd', 'fotos', 'start', 'end', 'stats', 'porStatusBarbero', 'porDiaBarbero'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'salario_base' => ['nullable', 'integer', 'min:0'],
            'monto_por_servicio' => ['nullable', 'integer', 'min:0'],
            'slot_minutes' => ['required', 'integer', 'in:15,20,30,45,60'],
            'work_start' => ['required', 'date_format:H:i'],
            'work_end' => ['required', 'date_format:H:i', 'after:work_start'],
            'work_days' => ['required', 'array', 'min:1'],
            'work_days.*' => ['integer', 'between:0,6'],
            'activo' => ['sometimes', 'boolean'],
            'buffer_minutes' => 'nullable|integer|min:0|max:120',
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['work_days'] = json_encode($data['work_days']);
        $data['activo'] = (bool)($data['activo'] ?? true);
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('uploads', 'public');
        }
        $data['photo_path'] = $image;
        Barbero::create($data);
        return back()->with('ok', 'Barbero creado');
    }


    public function update(Request $request, $id)
    {
        try {
            $barbero = Barbero::findOrFail($id);
            // update()            
            $data = $request->validate([
                'nombre' => ['required', 'string', 'max:120'],
                'email' => ['nullable', 'email', 'max:120'],
                'telefono' => ['nullable', 'string', 'max:50'],
                'salario_base' => ['nullable', 'integer', 'min:0'],
                'monto_por_servicio' => ['nullable', 'integer', 'min:0'],
                'slot_minutes' => ['required', 'integer', 'in:15,20,30,45,60'],
                'work_start' => ['required', 'date_format:H:i'],
                'work_end' => ['required', 'date_format:H:i', 'after:work_start'],
                'work_days' => ['required', 'array', 'min:1'],
                'work_days.*' => ['integer', 'between:0,6'],
                'activo' => ['sometimes', 'boolean'],
                'buffer_minutes' => 'nullable|integer|min:0|max:120'
            ]);
            $data['work_days'] = json_encode($data['work_days']);
            $data['activo'] = (bool)($data['activo'] ?? $barbero->activo);

            if ($request->hasFile('image')) {
                Storage::delete('public/' . $barbero->photo_path);
                $image = $request->file('image')->store('uploads', 'public');
                $data['photo_path'] = $image;
            }
            $barbero->update($data);
            return back()->with('ok', 'Barbero actualizado');
        } catch (\Exception $th) {
            dd($th->getMessage());
        }
    }


    public function destroy($id)
    {
        Barbero::findOrFail($id)->delete();
        return redirect('/barberos')->with('ok', 'Barbero eliminado');
    }


    public function services($id)
    {
        $barbero = Barbero::with(['servicios' => fn($q) => $q->orderBy('nombre')])->findOrFail($id);
        $allServicios = Servicio::where('activo', true)->orderBy('nombre')->get();
        return view('admin.barberos.services', compact('barbero', 'allServicios'));
    }


    public function storeService(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'barbero_id' => 'required|integer|exists:barberos,id',
            'servicio_id' => 'required|integer|exists:servicios,id',
            'price_view' => 'nullable|integer|min:0',          // EN colones desde la UI
            'duration_minutes' => 'nullable|integer|min:5|max:480',
            'activo' => 'sometimes|boolean'
        ]);
        $barbero = \App\Models\Barbero::findOrFail($data['barbero_id']);
        $pivot = [
            'price_cents' => isset($data['price_view']) ? (int)$data['price_view'] * 100 : null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'activo' => (bool)($data['activo'] ?? true),
        ];
        if ($barbero->servicios()->where('servicio_id', $data['servicio_id'])->exists()) {
            $barbero->servicios()->updateExistingPivot($data['servicio_id'], $pivot);
        } else {
            $barbero->servicios()->attach($data['servicio_id'], $pivot);
        }
        return back()->with('ok', 'Servicio asignado/actualizado');
    }

    public function destroyService($barbero_id, $servicioId)
    {
        try {
            $barbero = Barbero::findOrFail($barbero_id);
            $barbero->servicios()->detach($servicioId);
            return back()->with('ok', 'Servicio eliminado');
        } catch (\Exception $th) {
            return back()->with('ok', $th->getMessage());
        }
    }

    public function updateService(\Illuminate\Http\Request $request, $barbero_id, $servicio_id)
    {
        $data = $request->validate([
            'price_view'       => ['nullable', 'integer', 'min:0'],     // colones desde la UI
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:480'],
            'activo'           => ['sometimes', 'boolean'],
        ]);

        $barbero = \App\Models\Barbero::findOrFail($barbero_id);

        // Asegura que exista la relación
        if (!$barbero->servicios()->where('servicio_id', $servicio_id)->exists()) {
            return back()->with('error', 'El servicio no está asignado a este barbero.');
        }

        $pivot = [
            'price_cents'      => isset($data['price_view']) ? (int)$data['price_view'] * 100 : null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'activo'           => (bool)($data['activo'] ?? true),
        ];

        $barbero->servicios()->updateExistingPivot($servicio_id, $pivot);

        return back()->with('ok', 'Servicio actualizado');
    }
    public function storeExcepcion(Request $request, Barbero $barbero)
    {
        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'motivo' => 'nullable|string|max:120',
        ]);
        $barbero->excepciones()->create($data);
        return back()->with('ok', 'Día bloqueado');
    }
    public function destroyExcepcion(Barbero $barbero, $id)
    {
        $barbero->excepciones()->where('id', $id)->delete();
        return back()->with('ok', 'Excepción eliminada');
    }
    public function storeBloque(Request $request, Barbero $barbero)
    {
        $data = $request->validate([
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'motivo'     => 'nullable|string|max:120',
        ]);
        $barbero->bloques()->create($data);
        return back()->with('ok', 'Bloque creado');
    }
    public function destroyBloque(Request $request, Barbero $barbero, $id)
    {
        $barbero->bloques()->where('id', $id)->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('ok', 'Bloque eliminado');
    }
    public function agenda(\App\Models\Barbero $barbero)
    {
        // Vista completa para gestionar (incluye los parciales que ya te dejé)
        return view('admin.barberos.agenda', compact('barbero'));
    }
}
