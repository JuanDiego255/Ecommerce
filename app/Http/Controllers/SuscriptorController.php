<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Suscriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuscriptorController extends Controller
{
    //
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        //
        $suscriptors = Suscriptor::get();
        return view('admin.suscriptors.index', compact('suscriptors'));
    }
    public function index()
    {
        //
        return view('frontend.design_ecommerce.custom.index');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1) Validación (incluye reCAPTCHA)
        $request->validate([
            'tutor_name' => 'required|string|max:255',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email:rfc',
            'telephone'  => 'required|string|max:30',
            'birthday'   => 'required|date',
            'g-recaptcha-response' => 'required|captcha', // 👈 valida reCAPTCHA
        ], [
            'g-recaptcha-response.required' => 'Por favor confirma que no eres un robot.',
            'g-recaptcha-response.captcha'  => 'La verificación reCAPTCHA falló. Intenta de nuevo.',
        ]);

        DB::beginTransaction();
        try {
            $suscriptor = new Suscriptor();
            $suscriptor->tutor_name = $request->tutor_name;
            $suscriptor->name       = $request->name;
            $suscriptor->email      = $request->email;
            $suscriptor->telephone  = $request->telephone;
            $suscriptor->birthday   = $request->birthday;
            $suscriptor->save();

            DB::commit();
            return redirect()->back()->with(['status' => 'Gracias por suscribirte', 'icon' => 'success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            // opcional: Log::error($e);
            return redirect()->back()->withInput()->with(['status' => 'No se pudo guardar la suscripción', 'icon' => 'error']);
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

            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado el suscriptor con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar el suscriptor', 'icon' => 'error']);
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

            Suscriptor::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el suscriptor con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
