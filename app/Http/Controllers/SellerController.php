<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60; // Por ejemplo, 60 minutos
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sellers = Cache::remember('sellers', $this->expirationTime, function () {
            return Seller::get();
        });
        
        return view('admin.sellers.index', compact('sellers'));
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
            $seller =  new  Seller();
            if ($request->hasFile('image')) {
                $seller->image = $request->file('image')->store('uploads', 'public');
            }
            $seller->name = $request->name;
            $seller->position = $request->position;
            $seller->url_face = $request->url_face;
            $seller->url_insta = $request->url_insta;
            $seller->url_linkedin = $request->url_linkedin;
            $seller->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado el vendedor con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar el vendedor', 'icon' => 'error']);
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
            $seller = Seller::findOrfail($id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $seller->image);
                $image = $request->file('image')->store('uploads', 'public');
                $seller->image = $image;
            }
            $seller->name = $request->name;
            $seller->position = $request->position;
            $seller->url_face = $request->url_face;
            $seller->url_insta = $request->url_insta;
            $seller->url_linkedin = $request->url_linkedin;
            $seller->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado el vendedor con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar el vendedor', 'icon' => 'error']);
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

            $seller = Seller::findOrfail($id);            
            if (
                Storage::delete('public/' . $seller->image)
            ) {
                Seller::destroy($id);
            }
            Seller::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el vendedor con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
