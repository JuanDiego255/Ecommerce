<?php

namespace App\Http\Controllers;

use App\Models\AlertaPaciente;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AlertaPacienteController extends Controller
{
    public function store(Request $request, Paciente $paciente)
    {
        $request->validate([
            'tipo'        => 'required|string|max:80',
            'descripcion' => 'nullable|string|max:500',
            'nivel'       => 'required|in:info,warning,danger',
        ]);

        $paciente->alertas()->create([
            'tipo'        => $request->tipo,
            'descripcion' => $request->descripcion,
            'nivel'       => $request->nivel,
            'activa'      => true,
        ]);

        return redirect()->route('ecd.pacientes.show', $paciente)
            ->with('success', 'Alerta creada.');
    }

    public function resolve(Paciente $paciente, AlertaPaciente $alerta)
    {
        abort_unless($alerta->paciente_id === $paciente->id, 404);
        $alerta->update(['activa' => false]);

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('ecd.pacientes.show', $paciente)
            ->with('success', 'Alerta resuelta.');
    }

    public function destroy(Paciente $paciente, AlertaPaciente $alerta)
    {
        abort_unless($alerta->paciente_id === $paciente->id, 404);
        $alerta->delete();

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('ecd.pacientes.show', $paciente)
            ->with('success', 'Alerta eliminada.');
    }
}
