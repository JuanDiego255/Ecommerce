<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Tenant;
use App\Models\TenantPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantPaymentController extends Controller
{
    public function index()
    {
        $tenants = Tenant::where('tenants.id', '!=', 'main')
            ->where('active', 1)
            ->leftJoin('tenant_payments', 'tenants.id', 'tenant_payments.tenant_id')
            ->select(
                'tenants.id as id',
                'tenants.plan as plan',
                'tenants.cool_pay as cool_pay',
                'tenants.time_to_pay as time_to_pay',
                DB::raw('SUM(tenant_payments.payment) as total_payment'),
                DB::raw('MAX(tenant_payments.payment_date) as last_payment_date'),
                DB::raw('DATE_ADD(MAX(tenant_payments.payment_date), INTERVAL 1 MONTH) as payment_date')
            )
            ->groupBy('tenants.id', 'tenants.plan', 'tenants.cool_pay', 'tenants.time_to_pay')
            ->get();

        // ── Safewor bills ──────────────────────────────────────────────
        $billsSafewor = Bill::where('company', 'safewor')->orderByDesc('bill_date')->get();
        $billsSpace   = Bill::where('company', 'space360')->orderByDesc('bill_date')->get();

        // ── Safewor KPIs ───────────────────────────────────────────────
        $totalPayments      = (float) TenantPayment::sum('payment');
        $totalBillsSafewor  = (float) Bill::where('company', 'safewor')->sum('bill');
        $totalFundSafewor   = $totalPayments - $totalBillsSafewor;

        $monthPayments      = (float) TenantPayment::whereYear('payment_date', now()->year)
                                ->whereMonth('payment_date', now()->month)->sum('payment');
        $monthBillsSafewor  = (float) Bill::where('company', 'safewor')
                                ->whereYear('bill_date', now()->year)
                                ->whereMonth('bill_date', now()->month)->sum('bill');

        $overdueCount       = $tenants->filter(function ($t) {
            if (!$t->payment_date) return false;
            $due = \Carbon\Carbon::parse($t->payment_date)
                    ->addMonths(max(1, (int) $t->time_to_pay) - 1);
            return now() >= $due && $t->cool_pay != 1;
        })->count();

        // ── Space 360 KPIs ─────────────────────────────────────────────
        $totalBillsSpace  = (float) Bill::where('company', 'space360')->sum('bill');
        $monthBillsSpace  = (float) Bill::where('company', 'space360')
                                ->whereYear('bill_date', now()->year)
                                ->whereMonth('bill_date', now()->month)->sum('bill');

        return view('admin.tenant.tenants-pay', compact(
            'tenants',
            'billsSafewor', 'billsSpace',
            'totalPayments', 'totalBillsSafewor', 'totalFundSafewor',
            'monthPayments', 'monthBillsSafewor', 'overdueCount',
            'totalBillsSpace', 'monthBillsSpace'
        ));
    }

    public function indexPayment($id)
    {
        $payments = TenantPayment::where('tenant_id', $id)
                        ->orderByDesc('payment_date')
                        ->get();

        $tenant      = Tenant::where('id', $id)->firstOrFail();
        $totalPaid   = (float) $payments->sum('payment');
        $lastPayment = $payments->first();

        return view('admin.tenant.tenant-pay-id', compact(
            'payments', 'tenant', 'id', 'totalPaid', 'lastPayment'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $pay               = new TenantPayment();
            $pay->tenant_id    = $request->tenant_id;
            $pay->payment      = $request->payment;
            $pay->payment_date = $request->payment_date;
            $pay->save();
            DB::commit();
            return redirect()->back()
                ->with(['status' => 'Pago registrado con éxito', 'icon' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['status' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            TenantPayment::destroy($id);
            DB::commit();
            return redirect()->back()
                ->with(['status' => 'Pago eliminado', 'icon' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['status' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function toggleFreeze($id)
    {
        try {
            $tenant = Tenant::where('id', $id)->firstOrFail();
            $wasFrozen = (int) $tenant->cool_pay === 1;
            $tenant->cool_pay = $wasFrozen ? 0 : 1;
            $tenant->save();

            $msg = $wasFrozen
                ? "Inquilino {$id} activado con éxito"
                : "Inquilino {$id} congelado con éxito";

            return redirect()->back()->with(['status' => $msg, 'icon' => 'success']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['status' => $e->getMessage(), 'icon' => 'error']);
        }
    }
}
