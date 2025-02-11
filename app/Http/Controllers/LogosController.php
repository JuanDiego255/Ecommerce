<?php

namespace App\Http\Controllers;

use App\Models\Logos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LogosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $logos = Logos::get();
        return view('admin.logos.index',compact('logos'));
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
            $logo =  new  Logos();
            if ($request->hasFile('image')) {
                $logo->image = $request->file('image')->store('uploads', 'public');
            }
            $logo->name = $request->name;
            $logo->description = $request->description;
            $logo->is_supplier = $request->is_supplier ? 1 : 0;
            $logo->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado el logo con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar el logo', 'icon' => 'error']);
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
            $logo = Logos::findOrfail($id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $logo->image);
                $image = $request->file('image')->store('uploads', 'public');
                $logo->image = $image;
            }
            $logo->name = $request->name;           
            $logo->is_supplier = $request->is_supplier ? 1 : 0;
            $logo->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado el logo con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar el logo', 'icon' => 'error']);
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

            $logo = Logos::findOrfail($id);            
            if (
                Storage::delete('public/' . $logo->image)
            ) {
                Logos::destroy($id);
            }
            Logos::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el logo con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
