<?php

namespace App\Http\Controllers;

use App\Models\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoPagoController extends Controller
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
        $tipos = TipoPago::get();
        return view('admin.tipo_pagos.index',compact('tipos'));
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
            $tipo =  new  TipoPago();            
            $tipo->tipo = $request->tipo;            
            $tipo->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado el tipo de pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar el tipo de pago', 'icon' => 'error']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logos  $logos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        DB::beginTransaction();
        try {
            $tipo = TipoPago::findOrfail($id);            
            $tipo->tipo = $request->tipo;           
            $tipo->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado el tipo de pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar el tipo de pago', 'icon' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logos  $logos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {            
            TipoPago::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el tipo de pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
