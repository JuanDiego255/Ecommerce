<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Especialista;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SuperAdminController extends Controller
{
    public function overview(Request $request)
    {
        $this->authorize('superAdmin');


        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();


        $ingresos = Cita::query()
            ->whereBetween('starts_at', [$from, $to])
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_cents');


        $porBarbero = Cita::query()
            ->select('especialista_id', DB::raw('SUM(total_cents) as total'))
            ->whereBetween('starts_at', [$from, $to])
            ->whereIn('status', ['confirmed', 'completed'])
            ->groupBy('especialista_id')
            ->with('especialista:id,nombre') // asumiendo campo nombre
            ->get();


        return response()->json([
            'ingresos_totales_cents' => (int)$ingresos,
            'ingresos_por_barbero' => $porBarbero,
        ]);
    }
}
