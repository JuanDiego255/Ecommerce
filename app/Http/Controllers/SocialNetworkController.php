<?php

namespace App\Http\Controllers;

use App\Models\SocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SocialNetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $social = SocialNetwork::get();
        return view('admin.social.index', compact('social'));
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
                'description' => 'required|string|max:100',
                'url' => 'required|string|max:100'
            ];

            $mensaje = ["required" => 'El :attribute es requerido store'];
            $this->validate($request, $campos, $mensaje);

            $social =  new  SocialNetwork();
            if ($request->hasFile('image')) {
                $social->image = $request->file('image')->store('uploads', 'public');
            }
            $social->description = $request->description;
            $social->url = $request->url;
            $social->save();
            DB::commit();
            return redirect('/social-network')->with(['status' => 'Se ha guardado la fotografía con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/social-network')->with(['status' => 'No se pudo guardar la imagen', 'icon' => 'error']);
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
                'description' => 'required|string|max:100',
                'url' => 'required|string|max:100'
            ];

            $mensaje = ["required" => 'El :attribute es requerido ' . $id . ' update'];
            $this->validate($request, $campos, $mensaje);
            $social = SocialNetwork::findOrfail($id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $social->image);
                $image = $request->file('image')->store('uploads', 'public');
                $social->image = $image;
            }
            $social->description = $request->description;
            $social->url = $request->url;
            $social->update();
            DB::commit();
            return redirect('/social-network')->with(['status' => 'Se ha editado la fotografía con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/social-network')->with(['status' => 'No se pudo guardar la imagen', 'icon' => 'error']);
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

            $social = SocialNetwork::findOrfail($id);            
            if (
                Storage::delete('public/' . $social->image)
            ) {
                SocialNetwork::destroy($id);
            }
            SocialNetwork::destroy($id);
            DB::commit();
            return redirect('/social-network')->with(['status' => 'Se ha eliminado la fotografía con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
