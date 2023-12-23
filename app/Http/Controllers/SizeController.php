<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sizes = Size::get();
        return view('admin.sizes.index', compact('sizes'));
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

            $campos = [
                'size' => 'required|string|max:100'
            ];

            $mensaje = ["required" => 'El :attribute es requerido store'];
            $this->validate($request, $campos, $mensaje);

            $size =  new  Size();           
            $size->size = $request->size;
            $size->save();
            DB::commit();
            return redirect('/sizes')->with(['status' => 'Se ha guardado la talla con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/sizes')->with(['status' => 'No se pudo guardar la talla', 'icon' => 'error']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        DB::beginTransaction();
        try {
            $campos = [
                'size' => 'required|string|max:100'
            ];

            $mensaje = ["required" => 'El :attribute es requerido ' . $id . ' update'];
            $this->validate($request, $campos, $mensaje);
            $size = Size::findOrfail($id);            
            $size->size = $request->size;
            $size->update();
            DB::commit();
            return redirect('/sizes')->with(['status' => 'Se ha editado la talla con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/sizes')->with(['status' => 'No se pudo guardar la talla', 'icon' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {
            
            Size::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha borrado la talla con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
