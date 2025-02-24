<?php

namespace App\Http\Controllers;

use App\Models\ArqueoCaja;
use App\Models\MatriculaEstudiante;
use App\Models\PagosMatricula;
use App\Models\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagosMatriculaController extends Controller
{
    //
    public function index($id)
    {
        $info_estudiante = MatriculaEstudiante::join('estudiantes', 'matricula_estudiantes.estudiante_id', 'estudiantes.id')
            ->where('matricula_estudiantes.id', $id)
            ->select(
                'matricula_estudiantes.curso as curso',
                'matricula_estudiantes.monto_curso as monto_curso',
                'estudiantes.nombre as nombre_estudiante',
                'estudiantes.id as estudiante_id'
            )->first();
        $pagos_matricula = PagosMatricula::where('matricula_id', $id)
            ->join('tipo_pagos', 'pagos_matriculas.tipo_pago_id', 'tipo_pagos.id')
            ->select(
                'pagos_matriculas.*',
                'tipo_pagos.tipo as tipo_pago'
            )
            ->get();
        $tipo_pagos = TipoPago::get();
        return view('admin.estudiantes.matricula.pagos', compact('pagos_matricula', 'tipo_pagos', 'info_estudiante', 'id'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $cajaAbierta = ArqueoCaja::cajaAbiertaHoy($request->fecha_pago)->first();

            if (!$cajaAbierta) {
                return redirect()->back()->with(['status' => 'No hay ninguna caja abierta para el día de hoy', 'icon' => 'warning']);
            }
            $pago =  new  PagosMatricula();
            $pago->matricula_id = $id;
            $pago->arqueo_id = $cajaAbierta->id;
            $pago->user_id = Auth::user()->id;
            $pago->tipo_pago_id = $request->tipo_pago;
            $pago->monto_pago = $request->monto_pago;
            $pago->descuento = $request->descuento;
            $pago->fecha_pago = $request->fecha_pago;
            $pago->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha realizado el pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar el pago', 'icon' => 'error']);
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
        $cajaAbierta = ArqueoCaja::cajaAbiertaHoy($request->fecha_matricula)->first();

        if (!$cajaAbierta) {
            return redirect()->back()->with(['status' => 'No hay ninguna caja abierta para el día de hoy', 'icon' => 'warning']);
        }
        DB::beginTransaction();
        try {
            $pago = PagosMatricula::findOrfail($id);
            $pago->tipo_pago_id = $request->tipo_pago;
            $pago->monto_pago = $request->monto_pago;
            $pago->descuento = $request->descuento;
            $pago->fecha_pago = $request->fecha_pago;
            $pago->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado el pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar el pago', 'icon' => 'error']);
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
            PagosMatricula::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el pago con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
