<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EstudianteController extends Controller
{
    public function index(){
        $estudiantes = Estudiante::all();
        $tipo_pagos = TipoPago::get();
        return view('admin.estudiantes.index', compact('estudiantes','tipo_pagos'));
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
            $estudiante =  new  Estudiante();
            $estudiante->nombre = $request->nombre;
            $estudiante->telefono = $request->telefono;
            $estudiante->edad = $request->edad;
            $estudiante->email = $request->email;
            $estudiante->fecha_pago = $request->fecha_pago;
            $estudiante->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado el estudiante con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar el estudiante '.$th->getMessage(), 'icon' => 'error']);
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
            $estudiante = Estudiante::findOrfail($id);
            $estudiante->nombre = $request->nombre;
            $estudiante->telefono = $request->telefono;
            $estudiante->edad = $request->edad;
            $estudiante->email = $request->email;
            $estudiante->fecha_pago = $request->fecha_pago;
            $estudiante->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado el estudiante con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar el estudiante', 'icon' => 'error']);
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

            Estudiante::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el estudiante con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
