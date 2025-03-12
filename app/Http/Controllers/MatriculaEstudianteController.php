<?php

namespace App\Http\Controllers;

use App\Models\ArqueoCaja;
use App\Models\Estudiante;
use App\Models\MatriculaEstudiante;
use App\Models\PagosMatricula;
use App\Models\TipoPago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatriculaEstudianteController extends Controller
{
    public function index($id)
    {
        $item = Estudiante::where('id', $id)->first();
        $matriculas = MatriculaEstudiante::where('estudiante_id', $id)->get();
        $tipo_pagos = TipoPago::get();

        $diaPago = $item->fecha_pago;
        $fechaCostaRica = Carbon::now('America/Costa_Rica')->toDateString();

        // Iterar sobre las matrículas para calcular la próxima fecha de pago
        foreach ($matriculas as $matricula) {
            $ultimoPago = PagosMatricula::where('matricula_id', $matricula->id)
                ->where('tipo_venta', 1)
                ->orderBy('fecha_pago', 'desc')
                ->first();

            if ($ultimoPago) {
                // Si hay pagos, tomar la fecha del último pago
                $fechaReferencia = Carbon::parse($ultimoPago->fecha_pago);
            } else {
                // Si no hay pagos, tomar la fecha de la matrícula
                $fechaReferencia = Carbon::parse($matricula->created_at);
            }

            // Sumar un mes y ajustar el día de pago
            $proximaFechaPago = $fechaReferencia->addMonth()->day($diaPago);

            // Asegurar que la fecha es válida (evita días inexistentes, ej. 30 de febrero)
            if ($proximaFechaPago->day != $diaPago) {
                $proximaFechaPago->day($proximaFechaPago->daysInMonth);
            }

            // Agregar la próxima fecha de pago a cada matrícula
            $matricula->proxima_fecha_pago = $proximaFechaPago->toDateString();
        }
        return view('admin.estudiantes.matricula.index', compact('item', 'matriculas', 'tipo_pagos', 'fechaCostaRica'));
    }
    //
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function matriculaEstudiante($id, Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $cajaAbierta = ArqueoCaja::cajaAbiertaHoy($request->fecha_matricula)->first();

            if (!$cajaAbierta) {
                return redirect()->back()->with(['status' => 'No hay ninguna caja abierta para el día de hoy', 'icon' => 'warning']);
            }
            $matricula =  new  MatriculaEstudiante();
            $matricula->estudiante_id = $id;
            $matricula->arqueo_id = $cajaAbierta->id;
            $matricula->tipo_pago_id = $request->tipo_pago;
            $matricula->curso = $request->curso;
            $matricula->monto_pago = $request->monto_pago;
            $matricula->monto_curso = $request->monto_curso;
            $matricula->fecha_matricula = $request->fecha_matricula;
            $matricula->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha matriculado el estudiante con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la matricula', 'icon' => 'error']);
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
            $matricula = MatriculaEstudiante::findOrfail($id);
            $matricula->curso = $request->curso;
            $matricula->monto_pago = $request->monto_pago;
            $matricula->tipo_pago_id = $request->tipo_pago;
            $matricula->monto_curso = $request->monto_curso;
            $matricula->fecha_matricula = $request->fecha_matricula;
            $matricula->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado la matricula con éxito', 'icon' => 'success']);
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

            MatriculaEstudiante::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado la matrícula con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
