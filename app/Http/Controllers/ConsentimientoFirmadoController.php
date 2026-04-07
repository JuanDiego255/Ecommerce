<?php

namespace App\Http\Controllers;

use App\Models\ConsentimientoFirmado;
use App\Models\ConsentimientoPlantilla;
use App\Models\Paciente;
use App\Models\SesionClinica;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConsentimientoFirmadoController extends Controller
{
    /**
     * Show the consent signing page for a patient + session.
     */
    public function create(Paciente $paciente, SesionClinica $sesion, ConsentimientoPlantilla $plantilla)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);
        abort_unless($plantilla->activo, 404);

        // Interpolate dynamic variables
        $contenido = $this->interpolate($plantilla->contenido, $paciente, $sesion);

        return view('admin.ecd.consentimientos.firmar', compact('paciente', 'sesion', 'plantilla', 'contenido'));
    }

    /**
     * Store signed consent (base64 signature image + snapshot).
     */
    public function store(Request $request, Paciente $paciente, SesionClinica $sesion, ConsentimientoPlantilla $plantilla)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);

        $request->validate([
            'firma_base64' => 'required|string',
            'contenido_al_firmar' => 'required|string',
        ]);

        // Prevent duplicate signing
        if (ConsentimientoFirmado::where('paciente_id', $paciente->id)
                ->where('plantilla_id', $plantilla->id)
                ->where('sesion_id', $sesion->id)
                ->exists()) {
            return redirect()->route('ecd.sesiones.show', [$paciente, $sesion])
                ->with('success', 'El consentimiento ya había sido firmado anteriormente.');
        }

        // Decode and save signature image
        $base64 = $request->firma_base64;
        $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($base64);
        $firmaPath = 'consentimientos/firmas/' . uniqid('firma_', true) . '.png';
        Storage::disk('public')->put($firmaPath, $imageData);

        ConsentimientoFirmado::create([
            'paciente_id'         => $paciente->id,
            'plantilla_id'        => $plantilla->id,
            'sesion_id'           => $sesion->id,
            'contenido_al_firmar' => $request->contenido_al_firmar,
            'firma_path'          => $firmaPath,
            'ip_firma'            => $request->ip(),
            'firmado_en'          => Carbon::now(),
        ]);

        return redirect()->route('ecd.sesiones.show', [$paciente, $sesion])
            ->with('success', 'Consentimiento firmado y guardado correctamente.');
    }

    /**
     * Show list of firmados for a patient.
     */
    public function indexPaciente(Paciente $paciente)
    {
        $firmados = ConsentimientoFirmado::with('plantilla', 'sesion')
            ->where('paciente_id', $paciente->id)
            ->orderByDesc('firmado_en')
            ->get();

        return view('admin.ecd.consentimientos.firmados', compact('paciente', 'firmados'));
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function interpolate(string $text, Paciente $paciente, SesionClinica $sesion): string
    {
        return str_replace(
            ['{NOMBRE_PACIENTE}', '{CEDULA}', '{FECHA}', '{TRATAMIENTO}'],
            [
                $paciente->nombre_completo,
                $paciente->cedula ?? '—',
                now()->format('d/m/Y'),
                $sesion->titulo,
            ],
            $text
        );
    }
}
