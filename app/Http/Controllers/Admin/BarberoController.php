<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barbero;
use App\Models\BarberoHorario;
use App\Models\BarberoDescanso;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        $tab = $request->query('tab', 'info');
        $valid = ['info', 'servicios', 'agenda', 'calendario', 'galeria', 'stats'];
        $fotos = $barbero->trabajos()->latest()->get();
        if (!in_array($tab, $valid)) $tab = 'info';

        $back = $request->query('back', url('/barberos'));

        $barbero->loadCount(['citas']);
        $allServicios = Servicio::where('activo', true)->orderBy('nombre')->get();
        $workDays  = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];
        $slot      = (int)($barbero->slot_minutes ?? 30);
        $workStart = substr($barbero->work_start ?? '09:00', 0, 5);
        $workEnd   = substr($barbero->work_end   ?? '18:00', 0, 5);
        $horarios  = $barbero->horarios()->get();
        $descansos = $barbero->descansos()->get();

        $tz    = config('app.timezone', 'America/Costa_Rica');
        $start = $request->filled('start') ? Carbon::parse($request->input('start'), $tz)->startOfDay() : now($tz)->subDays(30)->startOfDay();
        $end   = $request->filled('end')   ? Carbon::parse($request->input('end'),   $tz)->endOfDay()   : now($tz)->endOfDay();

        $startUtc = $start->clone()->timezone('UTC');
        $endUtc   = $end->clone()->timezone('UTC');
        $stats = $porStatusBarbero = $porDiaBarbero = null;

        if ($tab === 'stats') {
            $q = \App\Models\Cita::where('barbero_id', $barbero->id)
                ->whereBetween('starts_at', [$startUtc, $endUtc]);

            $stats = [
                'total'     => (clone $q)->count(),
                'pending'   => (clone $q)->where('status', 'pending')->count(),
                'confirmed' => (clone $q)->where('status', 'confirmed')->count(),
                'completed' => (clone $q)->where('status', 'completed')->count(),
                'cancelled' => (clone $q)->where('status', 'cancelled')->count(),
                'ingresos'  => (clone $q)->whereIn('status', ['confirmed', 'completed'])->sum('total_cents'),
            ];
            $porDiaBarbero = (clone $q)
                ->select(DB::raw("DATE(CONVERT_TZ(starts_at, '+00:00', '" . now()->format('P') . "')) as d"), DB::raw('COUNT(*) as qty'))
                ->groupBy('d')
                ->orderBy('d')
                ->get();
            $porStatusBarbero = (clone $q)
                ->select('status', DB::raw('COUNT(*) as qty'))
                ->groupBy('status')
                ->pluck('qty', 'status');
        }

        return view('admin.barberos.profile', compact(
            'barbero', 'tab', 'back', 'allServicios',
            'workDays', 'slot', 'workStart', 'workEnd', 'horarios', 'descansos',
            'fotos', 'start', 'end', 'stats', 'porStatusBarbero', 'porDiaBarbero'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'             => ['required', 'string', 'max:120'],
            'email'              => ['nullable', 'email', 'max:120'],
            'telefono'           => ['nullable', 'string', 'max:50'],
            'salario_base'       => ['nullable', 'integer', 'min:0'],
            'monto_por_servicio' => ['nullable', 'integer', 'min:0'],
            'slot_minutes'       => ['required', 'integer', 'in:15,20,30,45,60'],
            'activo'             => ['sometimes', 'boolean'],
            'buffer_minutes'     => 'nullable|integer|min:0|max:120',
            'horarios'           => ['nullable', 'array'],
            'horarios.*.dias'    => ['required_with:horarios', 'array', 'min:1'],
            'horarios.*.dias.*'  => ['integer', 'between:0,6'],
            'horarios.*.hora_inicio' => ['required_with:horarios', 'date_format:H:i'],
            'horarios.*.hora_fin'    => ['required_with:horarios', 'date_format:H:i'],
            'descansos'              => ['nullable', 'array'],
            'descansos.*.dias'       => ['required_with:descansos', 'array', 'min:1'],
            'descansos.*.dias.*'     => ['integer', 'between:0,6'],
            'descansos.*.hora_inicio' => ['required_with:descansos', 'date_format:H:i'],
            'descansos.*.hora_fin'    => ['required_with:descansos', 'date_format:H:i'],
            'descansos.*.motivo'      => ['nullable', 'string', 'max:100'],
        ]);

        $horariosInput  = $request->input('horarios', []);
        $descansosInput = $request->input('descansos', []);

        // If no horarios blocks submitted, fall back to legacy fields
        if (empty($horariosInput)) {
            $legacyData = $request->validate([
                'work_start' => ['required', 'date_format:H:i'],
                'work_end'   => ['required', 'date_format:H:i', 'after:work_start'],
                'work_days'  => ['required', 'array', 'min:1'],
                'work_days.*' => ['integer', 'between:0,6'],
            ]);
            $data['work_start'] = $legacyData['work_start'];
            $data['work_end']   = $legacyData['work_end'];
            $data['work_days']  = json_encode($legacyData['work_days']);
        } else {
            $this->validateNoHorarioOverlaps($horariosInput);
            [$data['work_start'], $data['work_end'], $data['work_days']] = $this->deriveLeacyFields($horariosInput);
        }

        $data['activo'] = (bool)($data['activo'] ?? true);
        unset($data['horarios'], $data['descansos']);

        if ($request->hasFile('image')) {
            $data['photo_path'] = $request->file('image')->store('uploads', 'public');
        }

        $barbero = Barbero::create($data);

        if (!empty($horariosInput)) {
            $this->syncHorarios($barbero, $horariosInput);
        }
        $this->syncDescansos($barbero, $descansosInput);

        return back()->with('ok', 'Barbero creado');
    }

    public function update(Request $request, $id)
    {
        $barbero = Barbero::findOrFail($id);

        $data = $request->validate([
            'nombre'             => ['required', 'string', 'max:120'],
            'email'              => ['nullable', 'email', 'max:120'],
            'telefono'           => ['nullable', 'string', 'max:50'],
            'salario_base'       => ['nullable', 'integer', 'min:0'],
            'monto_por_servicio' => ['nullable', 'integer', 'min:0'],
            'slot_minutes'       => ['required', 'integer', 'in:15,20,30,45,60'],
            'activo'             => ['sometimes', 'boolean'],
            'buffer_minutes'     => 'nullable|integer|min:0|max:120',
            'horarios'           => ['nullable', 'array'],
            'horarios.*.dias'    => ['required_with:horarios', 'array', 'min:1'],
            'horarios.*.dias.*'  => ['integer', 'between:0,6'],
            'horarios.*.hora_inicio' => ['required_with:horarios', 'date_format:H:i'],
            'horarios.*.hora_fin'    => ['required_with:horarios', 'date_format:H:i'],
            'descansos'              => ['nullable', 'array'],
            'descansos.*.dias'       => ['required_with:descansos', 'array', 'min:1'],
            'descansos.*.dias.*'     => ['integer', 'between:0,6'],
            'descansos.*.hora_inicio' => ['required_with:descansos', 'date_format:H:i'],
            'descansos.*.hora_fin'    => ['required_with:descansos', 'date_format:H:i'],
            'descansos.*.motivo'      => ['nullable', 'string', 'max:100'],
        ]);

        $horariosInput  = $request->input('horarios', []);
        $descansosInput = $request->input('descansos', []);

        if (empty($horariosInput)) {
            $legacyData = $request->validate([
                'work_start' => ['required', 'date_format:H:i'],
                'work_end'   => ['required', 'date_format:H:i', 'after:work_start'],
                'work_days'  => ['required', 'array', 'min:1'],
                'work_days.*' => ['integer', 'between:0,6'],
            ]);
            $data['work_start'] = $legacyData['work_start'];
            $data['work_end']   = $legacyData['work_end'];
            $data['work_days']  = json_encode($legacyData['work_days']);
        } else {
            $this->validateNoHorarioOverlaps($horariosInput);
            [$data['work_start'], $data['work_end'], $data['work_days']] = $this->deriveLeacyFields($horariosInput);
        }

        $data['activo'] = (bool)($data['activo'] ?? $barbero->activo);
        unset($data['horarios'], $data['descansos']);

        if ($request->hasFile('image')) {
            if ($barbero->photo_path) Storage::delete('public/' . $barbero->photo_path);
            $data['photo_path'] = $request->file('image')->store('uploads', 'public');
        }

        $barbero->update($data);

        if (!empty($horariosInput)) {
            $this->syncHorarios($barbero, $horariosInput);
        }
        $this->syncDescansos($barbero, $descansosInput);

        return back()->with('ok', 'Barbero actualizado');
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

    public function storeService(Request $request)
    {
        $data = $request->validate([
            'barbero_id'       => 'required|integer|exists:barberos,id',
            'servicio_id'      => 'required|integer|exists:servicios,id',
            'price_view'       => 'nullable|integer|min:0',
            'duration_minutes' => 'nullable|integer|min:5|max:480',
            'activo'           => 'sometimes|boolean',
        ]);
        $barbero = Barbero::findOrFail($data['barbero_id']);
        $pivot = [
            'price_cents'      => isset($data['price_view']) ? (int)$data['price_view'] * 100 : null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'activo'           => (bool)($data['activo'] ?? true),
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
        $barbero = Barbero::findOrFail($barbero_id);
        $barbero->servicios()->detach($servicioId);
        return back()->with('ok', 'Servicio eliminado');
    }

    public function updateService(Request $request, $barbero_id, $servicio_id)
    {
        $data = $request->validate([
            'price_view'       => ['nullable', 'integer', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:480'],
            'activo'           => ['sometimes', 'boolean'],
        ]);

        $barbero = Barbero::findOrFail($barbero_id);

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
            'date'    => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date',
            'motivo'  => 'nullable|string|max:120',
        ]);

        $from = Carbon::parse($data['date'])->toDateString();
        $to   = Carbon::parse($data['date_to'])->toDateString();

        $solapa = $barbero->excepciones()
            ->whereDate('date', '<=', $to)
            ->whereDate('date_to', '>=', $from)
            ->exists();

        if ($solapa) {
            return back()
                ->withErrors(['date' => 'El rango se solapa con una excepción ya registrada.'])
                ->withInput();
        }

        $barbero->excepciones()->create([
            'date'    => $from,
            'date_to' => $to,
            'motivo'  => $data['motivo'] ?? null,
        ]);
        return back()->with('ok', 'Rango bloqueado');
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

    public function agenda(Barbero $barbero)
    {
        return view('admin.barberos.agenda', compact('barbero'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Saves horarios[] for a barbero, replacing any previous ones.
     */
    private function syncHorarios(Barbero $barbero, array $horariosInput): void
    {
        $barbero->horarios()->delete();
        foreach ($horariosInput as $h) {
            $barbero->horarios()->create([
                'dias'        => $h['dias'],
                'hora_inicio' => $h['hora_inicio'],
                'hora_fin'    => $h['hora_fin'],
            ]);
        }
    }

    /**
     * Replaces all recurring breaks for a barbero.
     * Passing an empty array removes all breaks (e.g. clear on form submit).
     */
    private function syncDescansos(Barbero $barbero, array $descansosInput): void
    {
        $barbero->descansos()->delete();
        foreach ($descansosInput as $d) {
            $barbero->descansos()->create([
                'dias'        => $d['dias'],
                'hora_inicio' => $d['hora_inicio'],
                'hora_fin'    => $d['hora_fin'],
                'motivo'      => $d['motivo'] ?? null,
            ]);
        }
    }

    /**
     * Throws ValidationException if any two horario blocks overlap on the same day.
     */
    private function validateNoHorarioOverlaps(array $horarios): void
    {
        // Build day → [intervals] map
        $byDay = [];
        foreach ($horarios as $idx => $h) {
            $start = strtotime($h['hora_inicio']);
            $end   = strtotime($h['hora_fin']);
            if ($end <= $start) {
                throw ValidationException::withMessages([
                    "horarios.$idx.hora_fin" => 'La hora de fin debe ser posterior a la hora de inicio.',
                ]);
            }
            foreach ($h['dias'] as $day) {
                $byDay[$day][] = ['start' => $start, 'end' => $end, 'idx' => $idx];
            }
        }

        foreach ($byDay as $day => $intervals) {
            usort($intervals, fn($a, $b) => $a['start'] - $b['start']);
            for ($i = 1; $i < count($intervals); $i++) {
                if ($intervals[$i]['start'] < $intervals[$i - 1]['end']) {
                    throw ValidationException::withMessages([
                        "horarios.{$intervals[$i]['idx']}.hora_inicio" => 'Los bloques de horario se superponen para el mismo día.',
                    ]);
                }
            }
        }
    }

    /**
     * Derives legacy work_start / work_end / work_days from horarios blocks
     * so existing queries against barberos table keep working.
     */
    private function deriveLeacyFields(array $horarios): array
    {
        $allDays  = [];
        $minStart = null;
        $maxEnd   = null;

        foreach ($horarios as $h) {
            foreach ($h['dias'] as $d) {
                $allDays[$d] = true;
            }
            if ($minStart === null || $h['hora_inicio'] < $minStart) $minStart = $h['hora_inicio'];
            if ($maxEnd   === null || $h['hora_fin']    > $maxEnd)   $maxEnd   = $h['hora_fin'];
        }

        return [
            $minStart ?? '09:00',
            $maxEnd   ?? '18:00',
            json_encode(array_keys($allDays)),
        ];
    }
}
