<?php

namespace App\Http\Controllers;

use App\Models\ConsentimientoFirmado;
use App\Models\Paciente;
use App\Models\SesionClinica;
use App\Models\SesionImagen;
use Illuminate\Http\Request;

class PortalPacienteController extends Controller
{
    /**
     * Resolve and validate a portal token, abort 403 if invalid/expired.
     */
    private function resolveToken(string $token): Paciente
    {
        $paciente = Paciente::where('portal_token', $token)->firstOrFail();

        if (! $paciente->hasActivePortalToken()) {
            abort(403, 'El enlace ha expirado. Solicita uno nuevo a tu especialista.');
        }

        return $paciente;
    }

    /**
     * Main portal page: profile + sessions list + quick stats.
     */
    public function show(string $token)
    {
        $paciente = $this->resolveToken($token);

        $sesiones = $paciente->sesiones()
            ->where('estado', 'completada')
            ->with(['imagenes' => fn ($q) => $q->orderBy('orden')])
            ->orderByDesc('fecha_sesion')
            ->get();

        $proximaCita = $paciente->sesiones()
            ->whereNotNull('proxima_cita')
            ->where('proxima_cita', '>=', today())
            ->orderBy('proxima_cita')
            ->value('proxima_cita');

        $firmados = $paciente->consentimientosFirmados()
            ->with('plantilla')
            ->orderByDesc('firmado_en')
            ->get();

        return view('portal.paciente.show', compact('paciente', 'sesiones', 'firmados', 'proximaCita', 'token'));
    }

    /**
     * Session detail page.
     */
    public function sesion(string $token, int $sesionId)
    {
        $paciente = $this->resolveToken($token);

        $sesion = SesionClinica::where('id', $sesionId)
            ->where('paciente_id', $paciente->id)
            ->where('estado', 'completada')
            ->with(['imagenes' => fn ($q) => $q->orderBy('tipo')->orderBy('orden')])
            ->firstOrFail();

        return view('portal.paciente.sesion', compact('paciente', 'sesion', 'token'));
    }

    /**
     * Consent document detail page.
     */
    public function consentimiento(string $token, int $firmadoId)
    {
        $paciente = $this->resolveToken($token);

        $firmado = ConsentimientoFirmado::where('id', $firmadoId)
            ->where('paciente_id', $paciente->id)
            ->with('plantilla', 'sesion')
            ->firstOrFail();

        return view('portal.paciente.consentimiento', compact('paciente', 'firmado', 'token'));
    }

    // ── Admin actions (protected by auth) ────────────────────────────────────

    /**
     * Generate or refresh the portal token for a patient.
     */
    public function generateToken(Request $request, Paciente $paciente)
    {
        $token = $paciente->generatePortalToken(days: 30);

        $url = route('portal.paciente.show', $token);

        if ($request->expectsJson()) {
            return response()->json(['url' => $url, 'token' => $token]);
        }

        return back()->with('portal_url', $url);
    }

    /**
     * Revoke the portal token.
     */
    public function revokeToken(Paciente $paciente)
    {
        $paciente->revokePortalToken();
        return back()->with('success', 'Acceso al portal revocado.');
    }
}
