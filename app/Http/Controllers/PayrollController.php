<?php

// app/Http/Controllers/PayrollController.php
namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    // Lista de nóminas + generar nueva
    public function index(Request $request)
    {
        $payrolls = Payroll::orderByDesc('week_start')->paginate(12);

        // sugerir semana actual (lunes-domingo)
        $tz = config('app.timezone', 'America/Costa_Rica');
        $start = Carbon::now($tz)->startOfWeek(Carbon::MONDAY)->toDateString();
        $end   = Carbon::now($tz)->endOfWeek(Carbon::SUNDAY)->toDateString();

        return view('admin.payroll.index', compact('payrolls', 'start', 'end'));
    }

    // Generar nómina para un rango semanal (si ya existe, re-calcular solo si está "open")
    public function generate(Request $request)
    {
        $request->validate([
            'week_start' => 'required|date',
            'week_end'  => 'required|date|after_or_equal:week_start'
        ]);

        $tz = config('app.timezone', 'America/Costa_Rica');
        $startLocal = Carbon::parse($request->week_start, $tz)->startOfDay();
        $endLocal   = Carbon::parse($request->week_end,   $tz)->endOfDay();

        // convertimos a UTC para filtrar citas almacenadas en UTC
        $startUtc = $startLocal->clone()->timezone('UTC');
        $endUtc   = $endLocal->clone()->timezone('UTC');

        // upsert de la nómina (una por rango)
        $payroll = Payroll::firstOrCreate([
            'week_start' => $startLocal->toDateString(),
            'week_end'   => $endLocal->toDateString(),
        ], ['status' => 'open']);

        if ($payroll->status !== 'open') {
            return redirect()->route('payroll.show', $payroll)->with('error', 'La nómina ya está cerrada. No se puede recalcular.');
        }

        // Calculamos por barbero (solo citas completadas en rango)
        $barberos = Barbero::orderBy('nombre')->get(['id', 'nombre', 'commission_rate']);
        DB::transaction(function () use ($barberos, $payroll, $startUtc, $endUtc) {
            // Limpia ítems previos (para recalcular si ya existían)
            $payroll->items()->delete();

            foreach ($barberos as $b) {
                $q = Cita::where('barbero_id', $b->id)
                    ->where('status', 'completed')
                    ->whereBetween('starts_at', [$startUtc, $endUtc]);

                $grossCents = (int) $q->sum('total_cents');
                $servicesCount = (int) $q->count();

                // si no tuvo nada esa semana, creamos igual el item con 0? (útil para ver lista completa)
                $rate = $b->commission_rate ?? 50.00; // default 50%
                $barberCents = (int) round($grossCents * ($rate / 100));
                $ownerCents  = max(0, $grossCents - $barberCents);

                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'barbero_id' => $b->id,
                    'services_count' => $servicesCount,
                    'gross_cents' => $grossCents,
                    'commission_rate' => $rate,
                    'barber_commission_cents' => $barberCents,
                    'owner_commission_cents'  => $ownerCents,
                    'adjustment_cents' => 0,
                ]);
            }
        });

        return redirect()->route('payroll.show', $payroll)->with('ok', 'Nómina generada/recalculada.');
    }

    // Vista de detalle de una nómina
    public function show(Payroll $payroll)
    {
        $items = $payroll->items()->with('barbero:id,nombre')->orderBy('barbero_id')->get();

        // totales
        $totals = [
            'services'   => (int) $items->sum('services_count'),
            'gross'      => (int) $items->sum('gross_cents'),
            'barber'     => (int) $items->sum('barber_commission_cents'),
            'adjust'     => (int) $items->sum('adjustment_cents'),
            'owner'      => (int) $items->sum('owner_commission_cents'),
            'final_barber' => (int) $items->sum(fn($i) => $i->barber_commission_cents + $i->adjustment_cents),
        ];

        return view('admin.payroll.show', compact('payroll', 'items', 'totals'));
    }

    // Cerrar / Reabrir nómina (bloquea recálculo y edición de comisión)
    public function close(Payroll $payroll)
    {
        if ($payroll->status !== 'open') return back()->with('error', 'La nómina no está abierta.');
        $payroll->update(['status' => 'closed']);
        return back()->with('ok', 'Nómina cerrada.');
    }
    public function reopen(Payroll $payroll)
    {
        if ($payroll->status !== 'closed') return back()->with('error', 'Solo se pueden reabrir nóminas cerradas.');
        $payroll->update(['status' => 'open']);
        return back()->with('ok', 'Nómina reabierta (ahora puedes recalcular).');
    }

    // Ajuste manual de un ítem o marcar pagado
    public function updateItem(Request $request, PayrollItem $item)
    {
        $request->validate([
            'adjustment_cents' => 'nullable|integer',
        ]);
        if ($item->payroll->status === 'open') {
            // permitir también editar commission_rate si quisieras, pero lo normal es dejarlo congelado
            $item->update([
                'adjustment_cents' => (int)($request->input('adjustment_cents', 0)) * 100,
            ]);
            return back()->with('ok', 'Ítem actualizado.');
        }
        return back()->with('error', 'No puedes editar ítems de una nómina cerrada.');
    }

    public function markItemPaid(PayrollItem $item)
    {
        $item->update(['paid_at' => now()]);
        return back()->with('ok', 'Ítem marcado como pagado.');
    }

    // Config rápida: comisiones por barbero
    public function config()
    {
        $barberos = Barbero::orderBy('nombre')->get(['id', 'nombre', 'commission_rate']);
        return view('admin.payroll.config', compact('barberos'));
    }

    public function updateBarberCommission(Request $request, Barbero $barbero)
    {
        $request->validate(['commission_rate' => 'nullable|numeric|min:0|max:100']);
        $barbero->update(['commission_rate' => $request->commission_rate]);
        return back()->with('ok', 'Comisión actualizada para ' . $barbero->nombre);
    }
    public function markAllPaid(Payroll $payroll)
    {
        // (Opcional) exigir nómina cerrada antes de pagar:
        // if ($payroll->status !== 'closed') return back()->with('error','Cierra la nómina antes de pagar.');

        $payroll->items()->whereNull('paid_at')->update(['paid_at' => now()]);
        return back()->with('ok', 'Todos los ítems fueron marcados como pagados.');
    }

    /** Exportar CSV de la nómina */
    public function exportCsv(Payroll $payroll)
    {
        $items = $payroll->items()->with('barbero:id,nombre')->orderBy('barbero_id')->get();

        $filename = sprintf(
            'nomina_%s_%s.csv',
            $payroll->week_start->format('Ymd'),
            $payroll->week_end->format('Ymd')
        );

        $response = new StreamedResponse(function () use ($items, $payroll) {
            $handle = fopen('php://output', 'w');

            // Encabezados
            fputcsv($handle, [
                'Semana',
                'Barbero',
                'Servicios',
                'Bruto (colones)',
                '% Comisión',
                'Barbero (colones)',
                'Ajuste (colones)',
                'Final barbero (colones)',
                'Owner (colones)',
                'Pagado',
                'Fecha pago'
            ]);

            foreach ($items as $it) {
                $grossCol     = (int)($it->gross_cents / 100);
                $barberoCol   = (int)($it->barber_commission_cents / 100);
                $ajusteCol    = (int)($it->adjustment_cents / 100);
                $finalBarbCol = (int)(($it->barber_commission_cents + $it->adjustment_cents) / 100);
                $ownerCol     = (int)($it->owner_commission_cents / 100);

                fputcsv($handle, [
                    $payroll->week_start->format('d/m/Y') . ' - ' . $payroll->week_end->format('d/m/Y'),
                    $it->barbero->nombre ?? ('#' . $it->barbero_id),
                    $it->services_count,
                    $grossCol,
                    number_format($it->commission_rate, 2, '.', ''),
                    $barberoCol,
                    $ajusteCol,
                    $finalBarbCol,
                    $ownerCol,
                    $it->paid_at ? 'Sí' : 'No',
                    $it->paid_at ? $it->paid_at->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
    public function exportPdf(Payroll $payroll)
    {
        // Cargamos lo mismo que usas en show:
        $items = $payroll->items()->with('barbero:id,nombre')->orderBy('barbero_id')->get();

        // Totales (si ya tienes un método que te los arma, úsalo; acá un cálculo rápido)
        $totals = [
            'services'      => (int) $items->sum('services_count'),
            'gross'         => (int) $items->sum('gross_cents'),
            'barber'        => (int) $items->sum('barber_commission_cents'),
            'adjust'        => (int) $items->sum('adjustment_cents'),
            'final_barber'  => (int) $items->sum(fn($i) => $i->barber_commission_cents + $i->adjustment_cents),
            'owner'         => (int) $items->sum('owner_commission_cents'),
        ];

        // Info del tenant si quieres mostrar logo/nombre (ajusta a tu modelo/relación real)
        $tenant = optional(auth()->user())->tenant ?? null; // reemplaza por lo que uses

        $pdf = Pdf::loadView('admin.payroll.pdf', [
            'payroll' => $payroll,
            'items'   => $items,
            'totals'  => $totals,
            'tenant'  => $tenant,
            'now'     => now(),
        ])->setPaper('a4');

        $filename = sprintf(
            'nomina_%s_%s.pdf',
            $payroll->week_start->format('Ymd'),
            $payroll->week_end->format('Ymd')
        );

        return $pdf->download($filename);
    }
}
