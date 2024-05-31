<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $bills = Bill::get();

        return view('admin.tenant.bills.index', compact('bills'));
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
            $new_bill =  new  Bill();           
            $new_bill->bill = $request->bill;
            $new_bill->detail = $request->detail;
            $new_bill->bill_date = $request->bill_date;
            $new_bill->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado el gasto con éxito', 'icon' => 'success']);
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
            
            Bill::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha borrado el gasto con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
