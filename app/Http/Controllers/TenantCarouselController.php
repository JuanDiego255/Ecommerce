<?php

namespace App\Http\Controllers;

use App\Models\TenantCarousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenantCarouselController extends Controller
{
   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $tenant_carousel =  new TenantCarousel();
            if ($request->hasFile('image')) {
                $tenant_carousel->image = $request->file('image')->store('uploads', 'public');
            }
            if ($request->hasFile('mobile_image')) {
                $tenant_carousel->mobile_image = $request->file('mobile_image')->store('uploads', 'public');
            }

            $tenant_carousel->text1 = $request->text1;
            $tenant_carousel->text2 = $request->text2; 
            $tenant_carousel->url = $request->url; 
            $tenant_carousel->link_text = $request->link_text;            

            $tenant_carousel->save();
            DB::commit();
            return redirect('/tenant-components')->with(['status' => 'Se ha guardado la imagen del carrusel', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/tenant-components')->with(['status' => 'No se pudo guardar la imagen del carrusel', 'icon' => 'error']);
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

            $tenantcarousel = TenantCarousel::findOrfail($id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $tenantcarousel->image);
                $image = $request->file('image')->store('uploads', 'public');
                $tenantcarousel->image = $image;
            }
            if ($request->hasFile('mobile_image')) {
                Storage::delete('public/' . $tenantcarousel->image);
                $image = $request->file('mobile_image')->store('uploads', 'public');
                $tenantcarousel->mobile_image = $image;
            }
            $tenantcarousel->text1 = $request->text1;
            $tenantcarousel->text2 = $request->text2;  
            $tenantcarousel->url = $request->url; 
            $tenantcarousel->link_text = $request->link_text;     
            
            $tenantcarousel->update();
            DB::commit();
            return redirect('/tenant-components')->with(['status' => 'Se ha editado la imagen del carrusel con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/tenant-components')->with(['status' => 'No se pudo editar la imagen del carrusel', 'icon' => 'error']);
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

            $tenantcarousel = TenantCarousel::findOrfail($id);
            if (
                Storage::delete('public/' . $tenantcarousel->image)
            ) {
                TenantCarousel::destroy($id);
            }
            TenantCarousel::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado la imagen del carousel', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
