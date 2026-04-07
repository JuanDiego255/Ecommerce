<?php

namespace App\Http\Controllers;

use App\Models\AlertaPaciente;
use App\Models\ExpedienteClinico;
use App\Models\Paciente;
use App\Models\SesionClinica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::withCount('sesiones')
            ->with('alertas')
            ->orderBy('nombre')
            ->get();

        return view('admin.ecd.pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('admin.ecd.pacientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'          => 'required|string|max:100',
            'apellidos'       => 'required|string|max:100',
            'cedula'          => 'nullable|string|max:30|unique:pacientes,cedula',
            'fecha_nacimiento' => 'nullable|date',
            'sexo'            => 'required|in:M,F,O',
            'telefono'        => 'nullable|string|max:30',
            'email'           => 'nullable|email|max:150',
            'ocupacion'       => 'nullable|string|max:100',
            'direccion'       => 'nullable|string|max:255',
            'ciudad'          => 'nullable|string|max:100',
            'grupo_sanguineo' => 'nullable|string|max:10',
            'fuente_referido' => 'nullable|string|max:100',
            'notas_internas'  => 'nullable|string',
            'foto_perfil'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('pacientes/fotos', 'public');
        }

        $paciente = Paciente::create($data);

        // Auto-create clinical file
        ExpedienteClinico::create([
            'paciente_id'      => $paciente->id,
            'numero_expediente' => ExpedienteClinico::generarNumero(),
            'fecha_apertura'   => now()->toDateString(),
        ]);

        return redirect()->route('ecd.pacientes.show', $paciente)
            ->with('success', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load([
            'expediente',
            'alertas',
            'sesiones' => fn($q) => $q->with('plantilla')->limit(10),
        ]);

        $totalSesiones = $paciente->sesiones()->count();

        return view('admin.ecd.pacientes.show', compact('paciente', 'totalSesiones'));
    }

    public function edit(Paciente $paciente)
    {
        return view('admin.ecd.pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'nombre'          => 'required|string|max:100',
            'apellidos'       => 'required|string|max:100',
            'cedula'          => 'nullable|string|max:30|unique:pacientes,cedula,' . $paciente->id,
            'fecha_nacimiento' => 'nullable|date',
            'sexo'            => 'required|in:M,F,O',
            'telefono'        => 'nullable|string|max:30',
            'email'           => 'nullable|email|max:150',
            'ocupacion'       => 'nullable|string|max:100',
            'direccion'       => 'nullable|string|max:255',
            'ciudad'          => 'nullable|string|max:100',
            'grupo_sanguineo' => 'nullable|string|max:10',
            'fuente_referido' => 'nullable|string|max:100',
            'notas_internas'  => 'nullable|string',
            'foto_perfil'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_perfil')) {
            if ($paciente->foto_perfil) {
                Storage::disk('public')->delete($paciente->foto_perfil);
            }
            $data['foto_perfil'] = $request->file('foto_perfil')->store('pacientes/fotos', 'public');
        }

        $paciente->update($data);

        return redirect()->route('ecd.pacientes.show', $paciente)
            ->with('success', 'Datos del paciente actualizados.');
    }

    public function destroy(Paciente $paciente)
    {
        if ($paciente->foto_perfil) {
            Storage::disk('public')->delete($paciente->foto_perfil);
        }
        $paciente->delete();

        return redirect()->route('ecd.pacientes.index')
            ->with('success', 'Paciente eliminado.');
    }

    // Historia clínica (expediente data)
    public function historia(Paciente $paciente)
    {
        $expediente = $paciente->expediente ?? ExpedienteClinico::create([
            'paciente_id'      => $paciente->id,
            'numero_expediente' => ExpedienteClinico::generarNumero(),
            'fecha_apertura'   => now()->toDateString(),
        ]);

        return view('admin.ecd.pacientes.historia', compact('paciente', 'expediente'));
    }

    public function updateHistoria(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'alergias'                     => 'nullable|string',
            'medicamentos_actuales'        => 'nullable|string',
            'condiciones_medicas'          => 'nullable|string',
            'antecedentes_familiares'      => 'nullable|string',
            'antecedentes_esteticos'       => 'nullable|string',
            'embarazo'                     => 'boolean',
            'lactancia'                    => 'boolean',
            'diabetes'                     => 'boolean',
            'hipertension'                 => 'boolean',
            'epilepsia'                    => 'boolean',
            'problemas_coagulacion'        => 'boolean',
            'piel_sensible'                => 'boolean',
            'queloides'                    => 'boolean',
            'rosacea'                      => 'boolean',
            'fuma'                         => 'boolean',
            'consume_alcohol'              => 'boolean',
            'observaciones_generales'      => 'nullable|string',
        ]);

        // Checkboxes: if not present in request, set to false
        $booleans = ['embarazo','lactancia','diabetes','hipertension','epilepsia',
                     'problemas_coagulacion','piel_sensible','queloides','rosacea',
                     'fuma','consume_alcohol'];
        foreach ($booleans as $bool) {
            $data[$bool] = $request->boolean($bool);
        }

        $expediente = $paciente->expediente;
        if ($expediente) {
            $expediente->update($data);
        } else {
            $paciente->expediente()->create(array_merge($data, [
                'numero_expediente' => ExpedienteClinico::generarNumero(),
                'fecha_apertura'   => now()->toDateString(),
            ]));
        }

        return redirect()->route('ecd.pacientes.historia', $paciente)
            ->with('success', 'Historia clínica actualizada.');
    }
}
