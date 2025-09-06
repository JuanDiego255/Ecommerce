<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Barbero;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $tz = config('app.timezone', 'America/Costa_Rica');

        // Rango: por defecto últimos 30 días
        $start = $request->filled('start') ? Carbon::parse($request->input('start'), $tz)->startOfDay() : now($tz)->subDays(30)->startOfDay();
        $end   = $request->filled('end')   ? Carbon::parse($request->input('end'),   $tz)->endOfDay() : now($tz)->endOfDay();

        // IMPORTANTE: si guardas en UTC, conviene convertir a UTC para filtrar por columnas starts_at/ends_at
        $startUtc = $start->clone()->timezone('UTC');
        $endUtc   = $end->clone()->timezone('UTC');

        // KPIs generales
        $baseQuery = Cita::whereBetween('starts_at', [$startUtc, $endUtc]);

        $totalCitas     = (clone $baseQuery)->count();
        $porAprobar     = (clone $baseQuery)->where('status', 'pending')->count();
        $confirmadas    = (clone $baseQuery)->where('status', 'confirmed')->count();
        $completadas    = (clone $baseQuery)->where('status', 'completed')->count();
        $canceladas     = (clone $baseQuery)->where('status', 'cancelled')->count();
        $ingresosCents  = (clone $baseQuery)->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_cents');

        // Ingresos por barbero
        $ingresosPorBarbero = (clone $baseQuery)
            ->select('barbero_id', DB::raw('SUM(total_cents) as total_cents'), DB::raw('COUNT(*) as citas'))
            ->whereIn('status', ['confirmed', 'completed'])
            ->groupBy('barbero_id')
            ->with('barbero:id,nombre')
            ->orderByDesc('total_cents')
            ->get();

        // Citas por status (para barra apilada/pastel)
        $porStatus = (clone $baseQuery)
            ->select('status', DB::raw('COUNT(*) as qty'))
            ->groupBy('status')
            ->pluck('qty', 'status'); // ['pending'=>x, ...]

        // (Opcional) Distribución temporal para chart (por día)
        $porDia = (clone $baseQuery)
            ->select(DB::raw("DATE(CONVERT_TZ(starts_at, '+00:00', '" . now()->offsetHours . ":00')) as d"), DB::raw('COUNT(*) as qty'))
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        // Barberos activos para filtro rápido (si quieres un select)
        $barberos = Barbero::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.owner.dashboard', compact(
            'start',
            'end',
            'totalCitas',
            'porAprobar',
            'confirmadas',
            'completadas',
            'canceladas',
            'ingresosCents',
            'ingresosPorBarbero',
            'porStatus',
            'porDia',
            'barberos'
        ));
    }

    // JSON para charts (opcional)
    public function data(Request $request)
    {
        // Igual que arriba, pero devuelve JSON con series para los gráficos
    }
}
