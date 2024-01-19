<?php

namespace App\Http\Controllers;

use App\Models\TenantSocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantSocialNetworkController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $campos = [
                'social_network' => 'required|string|max:1000',
                'url' => 'required|string|max:1000'
            ];

            $mensaje = ["required" => 'El :attribute es requerido store'];
            $this->validate($request, $campos, $mensaje);

            $social =  new TenantSocialNetwork();

            $social->social_network = $request->social_network;
            $social->url = $request->url;

            $social->save();
            DB::commit();

            return redirect('/tenant-info')->with(['status' => 'Se ha guardado la información de las redes sociales', 'icon' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
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
                'social_network' => 'required|string|max:1000',
                'url' => 'required|string|max:1000'
            ];

            $mensaje = ["required" => 'El :attribute es requerido ' . $id . ' update'];
            $this->validate($request, $campos, $mensaje);
            $social = TenantSocialNetwork::findOrfail($id);
            
            $social->social_network = $request->social_network;
            $social->url = $request->url;
            $social->update();
            DB::commit();
            return redirect('/tenant-info')->with(['status' => 'Se ha editado la información de las redes sociales con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/tenant-info')->with(['status' => 'No se pudo guardar la información de las redes sociales', 'icon' => 'error']);
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
            TenantSocialNetwork::destroy($id);
            DB::commit();
            return redirect('/tenant-info')->with(['status' => 'Se ha eliminado la red social', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
