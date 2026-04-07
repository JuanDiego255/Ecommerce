<?php

namespace App\Http\Controllers;

use App\Models\AlertaPaciente;
use App\Models\Paciente;
use App\Models\SesionClinica;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardECDController extends Controller
{
    public function index()
    {
        $hoy   = Carbon::today();
        $mes   = Carbon::now()->startOfMonth();
        $mesFin = Carbon::now()->endOfMonth();

        // ── Counts ──────────────────────────────────────────────────────────
        $totalPacientes   = Paciente::where('activo', true)->count();
        $nuevosEsteMes    = Paciente::where('activo', true)
            ->whereBetween('created_at', [$mes, $mesFin])
            ->count();
        $sesionesEsteMes  = SesionClinica::whereBetween('fecha_sesion', [$mes, $mesFin])->count();
        $sesionesHoy      = SesionClinica::whereDate('fecha_sesion', $hoy)->count();
        $alertasActivas   = AlertaPaciente::where('activa', true)->count();
        $proximasCitas    = SesionClinica::where('proxima_cita', '>=', $hoy)
            ->where('proxima_cita', '<=', Carbon::now()->addDays(14))
            ->count();

        // ── Sesiones por mes (últimos 6 meses) ──────────────────────────────
        $sesionesXMes = SesionClinica::select(
                DB::raw("DATE_FORMAT(fecha_sesion,'%Y-%m') as mes"),
                DB::raw('COUNT(*) as total')
            )
            ->where('fecha_sesion', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(fn($r) => [
                'mes'   => Carbon::createFromFormat('Y-m', $r->mes)->translatedFormat('M Y'),
                'total' => $r->total,
            ]);

        // ── Sesiones recientes ────────────────────────────────────────────
        $sesionesRecientes = SesionClinica::with(['paciente', 'especialista'])
            ->orderByDesc('fecha_sesion')
            ->limit(8)
            ->get();

        // ── Próximas citas ────────────────────────────────────────────────
        $proxCitas = SesionClinica::with('paciente')
            ->where('proxima_cita', '>=', $hoy)
            ->orderBy('proxima_cita')
            ->limit(6)
            ->get();

        // ── Top tratamientos (por título de sesión) ────────────────────────
        $topTratamientos = SesionClinica::select('titulo', DB::raw('COUNT(*) as total'))
            ->groupBy('titulo')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.ecd.dashboard.index', compact(
            'totalPacientes', 'nuevosEsteMes', 'sesionesEsteMes', 'sesionesHoy',
            'alertasActivas', 'proximasCitas', 'sesionesXMes',
            'sesionesRecientes', 'proxCitas', 'topTratamientos'
        ));
    }
}
