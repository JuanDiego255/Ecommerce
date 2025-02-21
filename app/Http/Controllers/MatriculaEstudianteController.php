<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\MatriculaEstudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatriculaEstudianteController extends Controller
{
    public function index($id)
    {
        $item = Estudiante::where('id', $id)->first();
        $matriculas = MatriculaEstudiante::where('estudiante_id', $id)->get();
        return view('admin.estudiantes.matricula.index', compact('item', 'matriculas'));
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
            $matricula =  new  MatriculaEstudiante();
            $matricula->estudiante_id = $id;
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
        DB::beginTransaction();
        try {
            $matricula = MatriculaEstudiante::findOrfail($id);
            $matricula->curso = $request->curso;
            $matricula->monto_pago = $request->monto_pago;
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
