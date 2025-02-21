<?php

namespace App\Http\Controllers;

use App\Models\ArqueoCaja;
use App\Models\Cajas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cajas = Cajas::leftJoin('arqueo_cajas as ac', function ($join) {
            $join->on('cajas.id', '=', 'ac.caja_id')
                ->where('ac.estado', '=', 1);
        })
            ->select(
                'cajas.id as id',
                'cajas.nombre as nombre',
                'ac.id as arqueo_id',
                'ac.estado as estado'
            )
            ->get();

        return view('admin.cajas.index', compact('cajas'));
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
            $caja =  new  Cajas();
            $caja->nombre = $request->nombre;
            $caja->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado la caja con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la caja', 'icon' => 'error']);
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
            $caja = Cajas::findOrfail($id);
            $caja->nombre = $request->nombre;
            $caja->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado la caja con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar la caja', 'icon' => 'error']);
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
            Cajas::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado la caja con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function open($id)
    {
        DB::beginTransaction();
        try {
            $fechaCostaRica = Carbon::now('America/Costa_Rica')->toDateString(); // Obtiene la fecha sin hora

            // Verificar si ya hay una caja abierta hoy
            $cajaAbierta = DB::table('arqueo_cajas')
                ->whereDate('fecha_ini', $fechaCostaRica)
                ->where('estado', 1)
                ->where('caja_id', $id)
                ->exists();

            if ($cajaAbierta) {
                return redirect()->back()->with(['status' => 'Ya hay una caja abierta hoy', 'icon' => 'warning']);
            }

            // Abrir una nueva caja
            $caja = new ArqueoCaja();
            $caja->caja_id = $id;
            $caja->user_id = Auth::user()->id;
            $caja->fecha_ini = $fechaCostaRica;
            $caja->estado = 1;
            $caja->save();

            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha abierto la caja con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo abrir la caja: '.$th->getMessage(), 'icon' => 'error']);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function close($id)
    {
        DB::beginTransaction();
        try {
            $fechaCostaRica = Carbon::now('America/Costa_Rica')->toDateString(); // Obtiene la fecha sin hora

            // Verificar si ya hay una caja abierta hoy
            $cajaAbierta = DB::table('arqueo_cajas')
                ->whereDate('fecha_ini', $fechaCostaRica)
                ->where('estado', 1)
                ->where('caja_id', $id)
                ->exists();

            if ($cajaAbierta) {
                $caja = ArqueoCaja::find($id);
                $caja->fecha_fin = $fechaCostaRica;
                $caja->estado = 0;
                $caja->update();
            }

            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha cerrado la caja con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo cerrar la caja: '.$th->getMessage(), 'icon' => 'error']);
        }
    }
}
