<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\SesionClinica;

class ReporteECDController extends Controller
{
    /**
     * Printable session report.
     */
    public function sesion(Paciente $paciente, SesionClinica $sesion)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);

        $sesion->load(['plantilla', 'respuestas', 'imagenes', 'especialista']);
        $camposPlano = $sesion->plantilla?->campos_plano ?? [];

        return view('admin.ecd.reportes.sesion', compact('paciente', 'sesion', 'camposPlano'));
    }

    /**
     * Printable full patient expediente report.
     */
    public function expediente(Paciente $paciente)
    {
        $paciente->load([
            'expediente',
            'alertas',
            'sesiones' => fn($q) => $q->with(['plantilla', 'respuestas', 'imagenes', 'especialista'])
                                      ->where('estado', 'completada')
                                      ->orderBy('fecha_sesion'),
            'consentimientosFirmados' => fn($q) => $q->with('plantilla')->orderBy('firmado_en'),
        ]);

        return view('admin.ecd.reportes.expediente', compact('paciente'));
    }
}
