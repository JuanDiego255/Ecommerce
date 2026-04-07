<?php

namespace App\Http\Controllers;

use App\Models\FichaPlantilla;
use App\Models\Paciente;
use App\Models\SesionClinica;
use App\Models\SesionRespuesta;
use App\Models\Especialista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SesionClinicaController extends Controller
{
    public function create(Paciente $paciente)
    {
        $plantillas   = FichaPlantilla::where('activa', true)->orderBy('nombre')->get();
        $especialistas = Especialista::orderBy('nombre')->get();

        return view('admin.ecd.sesiones.create', compact('paciente', 'plantillas', 'especialistas'));
    }

    public function store(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'plantilla_id'       => 'nullable|exists:ficha_plantillas,id',
            'especialista_id'    => 'nullable|exists:especialistas,id',
            'titulo'             => 'required|string|max:200',
            'fecha_sesion'       => 'required|date',
            'hora_inicio'        => 'nullable|date_format:H:i',
            'hora_fin'           => 'nullable|date_format:H:i',
            'estado'             => 'required|in:borrador,completada,cancelada',
            'observaciones_pre'  => 'nullable|string',
            'observaciones_post' => 'nullable|string',
            'productos_usados'   => 'nullable|string',
            'recomendaciones'    => 'nullable|string',
            'proxima_cita'       => 'nullable|date',
            'notas_internas'     => 'nullable|string',
        ]);

        $data['paciente_id'] = $paciente->id;
        $data['created_by']  = Auth::id();

        $sesion = DB::transaction(function () use ($data, $request, $paciente) {
            $sesion = SesionClinica::create($data);

            // Save dynamic form responses
            if ($request->has('respuestas') && is_array($request->respuestas)) {
                foreach ($request->respuestas as $key => $valor) {
                    if ($valor === null || $valor === '') continue;
                    $valorStr = is_array($valor) ? json_encode($valor) : (string) $valor;
                    SesionRespuesta::updateOrCreate(
                        ['sesion_id' => $sesion->id, 'campo_key' => $key],
                        ['campo_tipo' => $request->input("tipos.{$key}", 'texto'), 'valor' => $valorStr]
                    );
                }
            }

            // Update last visit date on clinical file
            if ($paciente->expediente) {
                $paciente->expediente->update(['ultima_visita' => $data['fecha_sesion']]);
            }

            return $sesion;
        });

        return redirect()->route('ecd.sesiones.show', [$paciente, $sesion])
            ->with('success', 'Sesión registrada correctamente.');
    }

    public function show(Paciente $paciente, SesionClinica $sesion)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);

        $sesion->load(['plantilla', 'respuestas', 'imagenes', 'especialista']);

        $camposPlano = $sesion->plantilla?->campos_plano ?? [];

        return view('admin.ecd.sesiones.show', compact('paciente', 'sesion', 'camposPlano'));
    }

    public function edit(Paciente $paciente, SesionClinica $sesion)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);

        $sesion->load(['plantilla', 'respuestas', 'especialista']);
        $plantillas    = FichaPlantilla::where('activa', true)->orderBy('nombre')->get();
        $especialistas = Especialista::orderBy('nombre')->get();

        // Build keyed responses map for easy template access
        $respuestasMap = $sesion->respuestas->keyBy('campo_key');

        return view('admin.ecd.sesiones.edit', compact('paciente', 'sesion', 'plantillas', 'especialistas', 'respuestasMap'));
    }

    public function update(Request $request, Paciente $paciente, SesionClinica $sesion)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);

        $data = $request->validate([
            'plantilla_id'       => 'nullable|exists:ficha_plantillas,id',
            'especialista_id'    => 'nullable|exists:especialistas,id',
            'titulo'             => 'required|string|max:200',
            'fecha_sesion'       => 'required|date',
            'hora_inicio'        => 'nullable|date_format:H:i',
            'hora_fin'           => 'nullable|date_format:H:i',
            'estado'             => 'required|in:borrador,completada,cancelada',
            'observaciones_pre'  => 'nullable|string',
            'observaciones_post' => 'nullable|string',
            'productos_usados'   => 'nullable|string',
            'recomendaciones'    => 'nullable|string',
            'proxima_cita'       => 'nullable|date',
            'notas_internas'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $request, $paciente, $sesion) {
            $sesion->update($data);

            if ($request->has('respuestas') && is_array($request->respuestas)) {
                foreach ($request->respuestas as $key => $valor) {
                    $valorStr = is_array($valor) ? json_encode($valor) : (string) $valor;
                    SesionRespuesta::updateOrCreate(
                        ['sesion_id' => $sesion->id, 'campo_key' => $key],
                        ['campo_tipo' => $request->input("tipos.{$key}", 'texto'), 'valor' => $valorStr]
                    );
                }
            }
        });

        return redirect()->route('ecd.sesiones.show', [$paciente, $sesion])
            ->with('success', 'Sesión actualizada.');
    }

    public function destroy(Paciente $paciente, SesionClinica $sesion)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);
        $sesion->delete();

        return redirect()->route('ecd.pacientes.show', $paciente)
            ->with('success', 'Sesión eliminada.');
    }
}
