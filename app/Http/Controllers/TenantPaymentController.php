<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Tenant;
use App\Models\TenantPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tenants = Tenant::where('tenants.id', '!=', 'main')
        ->where('active',1)
        ->leftJoin('tenant_payments', 'tenants.id', 'tenant_payments.tenant_id')
        ->select(
            'tenants.id as id',
            'tenants.plan as plan',
            'tenants.cool_pay as cool_pay',
            'tenants.time_to_pay as time_to_pay',
            DB::raw('SUM(tenant_payments.payment) as total_payment'),
            DB::raw('DATE_ADD(MAX(tenant_payments.payment_date), INTERVAL 1 MONTH) as payment_date')
        )->groupBy('tenants.id','tenants.plan','tenants.cool_pay','tenants.time_to_pay')
        ->get();
        $bills = Bill::get();

        return view('admin.tenant.tenants-pay', compact('tenants','bills'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPayment($id)
    {
        //
        $tenants = TenantPayment::where('tenant_id', $id)
        ->get();
        $name = Tenant::where('id',$id)->first()->id;
        return view('admin.tenant.tenant-pay-id', compact('tenants','name','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $payment_new =  new  TenantPayment();           
            $payment_new->tenant_id = $request->tenant_id;
            $payment_new->payment = $request->payment;
            $payment_new->payment_date = $request->payment_date;
            $payment_new->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado el pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => $th->getMessage(), 'icon' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenantPayment  $tenantPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {
            
            TenantPayment::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha borrado el pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
