<?php

namespace App\Http\Controllers;

use App\Models\AddressUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user_id = Auth::user()->id;
        $address = AddressUser::where('user_id',$user_id)->get();
        return view('frontend.address.index', compact('address'));
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
            $user_id = Auth::user()->id;

            $campos = [
                'address' => 'required|string|max:100',
                'city' => 'required|string|max:30',
                'province' => 'required|string|max:30',
                'country' => 'required|string|max:30',
                'postal_code' => 'required|string|max:15'
            ];

            $mensaje = ["required" => 'El :attribute es requerido store'];
            $this->validate($request, $campos, $mensaje);

            $address =  new  AddressUser();
            $address->user_id = $user_id;
            $address->address = $request->address;
            $address->address_two = $request->address_two;
            $address->city = $request->city;
            $address->province = $request->province;
            $address->country = $request->country;
            $address->postal_code = $request->postal_code;
            $address->status = 0;
            $address->save();
            DB::commit();
            return redirect('/address')->with(['status' => 'Se ha guardado la dirección con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/address')->with(['status' => 'No se pudo guardar la dirección dirección', 'icon' => 'error']);
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
            $user_id = Auth::user()->id;
            $campos = [
                'address' => 'required|string|max:100',
                'city' => 'required|string|max:30',
                'province' => 'required|string|max:30',
                'country' => 'required|string|max:30',
                'postal_code' => 'required|string|max:15'
            ];

            $mensaje = ["required" => 'El :attribute es requerido ' . $id . ' update'];
            $this->validate($request, $campos, $mensaje);
            $address = AddressUser::findOrfail($id);
            $address->user_id = $user_id;
            $address->address = $request->address;
            $address->address_two = $request->address_two;
            $address->city = $request->city;
            $address->province = $request->province;
            $address->country = $request->country;
            $address->postal_code = $request->postal_code;
            $address->status = 0;
            $address->update();
            DB::commit();
            return redirect('/address')->with(['status' => 'Se ha editado la dirección con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/address')->with(['status' => 'No se pudo actualizar la dirección', 'icon' => 'error']);
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
            AddressUser::destroy($id);
            DB::commit();
            return redirect('/address')->with(['status' => 'Se ha eliminado la dirección con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        //
        DB::beginTransaction();
        try {         
            AddressUser::where('id', '!=', $id)->update(['status' => 0]);
            AddressUser::where('id', $id)->update(['status' => 1]);
            DB::commit();
            return redirect('/address')->with(['status' => 'Se ha seleccionado esta dirección como predeterminado', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
