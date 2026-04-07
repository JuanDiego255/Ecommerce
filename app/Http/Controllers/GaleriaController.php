<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\SesionClinica;
use App\Models\SesionImagen;

class GaleriaController extends Controller
{
    /**
     * All images for a patient, grouped by type.
     */
    public function index(Paciente $paciente)
    {
        $imagenes = SesionImagen::where('paciente_id', $paciente->id)
            ->with('sesion:id,titulo,fecha_sesion')
            ->orderByDesc('created_at')
            ->get();

        $grouped = $imagenes->groupBy('tipo');
        $tipos   = ['antes', 'durante', 'despues', 'referencia'];

        return view('admin.ecd.galeria.index', compact('paciente', 'grouped', 'tipos'));
    }

    /**
     * Before/after comparison for a specific session.
     */
    public function comparar(Paciente $paciente, SesionClinica $sesion)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);

        $sesion->load('imagenes');
        $antes   = $sesion->imagenes->where('tipo', 'antes')->values();
        $despues = $sesion->imagenes->where('tipo', 'despues')->values();

        return view('admin.ecd.galeria.comparar', compact('paciente', 'sesion', 'antes', 'despues'));
    }
}
