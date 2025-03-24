<?php

namespace App\Http\Controllers;

use App\Models\Metrica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MetricaController extends Controller
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
        $metricas = Metrica::get();
        return view('admin.metrica.index', compact('metricas'));
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
            $metrica =  new  Metrica();
            if ($request->hasFile('image')) {
                $metrica->image = $request->file('image')->store('uploads', 'public');
            }
            $metrica->titulo = $request->titulo;
            $metrica->valor = $request->valor;
            $metrica->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado la métrica con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la métrica', 'icon' => 'error']);
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
            $metrica = Metrica::findOrfail($id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $metrica->image);
                $image = $request->file('image')->store('uploads', 'public');
                $metrica->image = $image;
            }
            $metrica->titulo = $request->titulo;
            $metrica->valor = $request->valor;
            $metrica->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado la métrica con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar la métrica', 'icon' => 'error']);
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

            $metrica = Metrica::findOrfail($id);            
            if (
                Storage::delete('public/' . $metrica->image)
            ) {
                Metrica::destroy($id);
            }
            Metrica::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado la métrica con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
