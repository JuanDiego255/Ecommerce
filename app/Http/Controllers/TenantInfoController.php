<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\TenantCarousel;
use App\Models\TenantInfo;
use App\Models\TenantSocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenantInfoController extends Controller
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
        $data = Cache::remember('tenant_info_data', $this->expirationTime, function () {
            $tenant_info = TenantInfo::get();
            $tenantsocial = TenantSocialNetwork::get();            
            return compact('tenant_info', 'tenantsocial');
        });
        return view('admin.tenant-info.index', $data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexComponents()
    {
        //
        $data = Cache::remember('tenant_info_data', $this->expirationTime, function () {
            $tenant_info = TenantInfo::get();
            $tenantcarousel = TenantCarousel::get();
            $siteButtons = [
                'Inicio' => 'fa-home',
                'Categorias' => 'fa-th-list',
                'Carrito' => 'fa-shopping-cart',
                'Mis compras' => 'fa-shopping-bag',
                'Direcciones' => 'fa-map-marker-alt',
                'Usuario' => 'fa-user',
                'Comentarios' => 'fa-comments',
                'Blog' => 'fa-blog',
                'Servicios' => 'fa-concierge-bell'
            ];
    
            $fontAwesomeIcons = [
                'fa-address-book', 'fa-address-card', 'fa-angry', 'fa-arrow-alt-circle-down',
                'fa-arrow-alt-circle-left', 'fa-arrow-alt-circle-right', 'fa-arrow-alt-circle-up',
                'fa-bell', 'fa-bell-slash', 'fa-bookmark', 'fa-building', 'fa-calendar',
                'fa-calendar-alt', 'fa-calendar-check', 'fa-calendar-minus', 'fa-calendar-plus',
                'fa-calendar-times', 'fa-caret-square-down', 'fa-caret-square-left',
                'fa-caret-square-right', 'fa-caret-square-up', 'fa-chart-bar', 'fa-check-circle',
                'fa-check-square', 'fa-circle', 'fa-clipboard', 'fa-clock', 'fa-clone',
                'fa-closed-captioning', 'fa-comment', 'fa-comment-alt', 'fa-comment-dots',
                'fa-comments', 'fa-compass', 'fa-copy', 'fa-copyright', 'fa-credit-card',
                'fa-dizzy', 'fa-dot-circle', 'fa-edit', 'fa-envelope', 'fa-envelope-open',
                'fa-eye', 'fa-eye-slash', 'fa-file', 'fa-file-alt', 'fa-file-archive',
                'fa-file-audio', 'fa-file-code', 'fa-file-excel', 'fa-file-image', 'fa-file-pdf',
                'fa-file-powerpoint', 'fa-file-video', 'fa-file-word', 'fa-flag', 'fa-flushed',
                'fa-folder', 'fa-folder-open', 'fa-frown', 'fa-frown-open', 'fa-futbol',
                'fa-gem', 'fa-grimace', 'fa-grin', 'fa-grin-alt', 'fa-grin-beam',
                'fa-grin-beam-sweat', 'fa-grin-hearts', 'fa-grin-squint', 'fa-grin-squint-tears',
                'fa-grin-stars', 'fa-grin-tears', 'fa-grin-tongue', 'fa-grin-tongue-squint',
                'fa-grin-tongue-wink', 'fa-grin-wink', 'fa-hand-lizard', 'fa-hand-paper',
                'fa-hand-peace', 'fa-hand-point-down', 'fa-hand-point-left', 'fa-hand-point-right',
                'fa-hand-point-up', 'fa-hand-pointer', 'fa-hand-rock', 'fa-hand-scissors',
                'fa-hand-spock', 'fa-handshake', 'fa-hdd', 'fa-heart', 'fa-hospital', 'fa-hourglass',
                'fa-id-badge', 'fa-id-card', 'fa-image', 'fa-images', 'fa-keyboard', 'fa-kiss',
                'fa-kiss-beam', 'fa-kiss-wink-heart', 'fa-laugh', 'fa-laugh-beam', 'fa-laugh-squint',
                'fa-laugh-wink', 'fa-lemon', 'fa-life-ring', 'fa-lightbulb', 'fa-list-alt',
                'fa-map', 'fa-meh', 'fa-meh-blank', 'fa-meh-rolling-eyes', 'fa-minus-square',
                'fa-money-bill-alt', 'fa-moon', 'fa-newspaper', 'fa-object-group', 'fa-object-ungroup',
                'fa-paper-plane', 'fa-pause-circle', 'fa-play-circle', 'fa-plus-square',
                'fa-question-circle', 'fa-registered', 'fa-sad-cry', 'fa-sad-tear', 'fa-save',
                'fa-share-square', 'fa-smile', 'fa-smile-beam', 'fa-smile-wink', 'fa-snowflake',
                'fa-square', 'fa-star', 'fa-star-half', 'fa-sticky-note', 'fa-stop-circle',
                'fa-sun', 'fa-surprise', 'fa-thumbs-down', 'fa-thumbs-up', 'fa-times-circle',
                'fa-tired', 'fa-trash-alt', 'fa-user', 'fa-user-circle', 'fa-window-close',
                'fa-window-maximize', 'fa-window-minimize', 'fa-window-restore'
            ];
            return compact('tenant_info','tenantcarousel','fontAwesomeIcons','siteButtons');
        });        

        return view('admin.tenant-info.components', $data);
    }

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
            $campos = [
                'title' => 'required|string|max:1000',
                'mision' => 'required|string|max:1000',
                'title_suscrib_a' => 'required|string|max:1000',
                'description_suscrib' => 'required|string|max:1000',
                'footer' => 'required|string|max:1000',
                'whatsapp' => 'required|string|max:1000',
                'sinpe' => 'required|string|max:1000'
            ];

            $mensaje = ["required" => 'El :attribute es requerido store'];
            $this->validate($request, $campos, $mensaje);

            $tenantinfo =  new TenantInfo();
            if ($request->hasFile('logo')) {
                $tenantinfo->logo = $request->file('logo')->store('uploads', 'public');
            }
            if ($request->hasFile('login_image')) {
                $tenantinfo->login_image = $request->file('login_image')->store('uploads', 'public');
            }

            $tenantinfo->title = $request->title;
            $tenantinfo->title_discount = $request->title_discount;
            $tenantinfo->title_instagram = $request->title_instagram;
            $tenantinfo->mision = $request->mision;
            $tenantinfo->title_trend = $request->title_trend;
            $tenantinfo->title_suscrib_a = $request->title_suscrib_a;
            $tenantinfo->description_suscrib = $request->description_suscrib;
            $tenantinfo->footer = $request->footer;
            $tenantinfo->whatsapp = $request->whatsapp;
            $tenantinfo->sinpe = $request->sinpe;
            $tenantinfo->count = $request->count;
            $tenantinfo->email = $request->email;
            $tenantinfo->delivery = $request->delivery;

            $tenantinfo->save();
            DB::commit();
            return redirect('/tenant-info')->with(['status' => 'Se ha guardado la información del negocio', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/tenant-info')->with(['status' => 'No se pudo guardar la información del negocio', 'icon' => 'error']);
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
                'title' => 'required|string|max:1000',
                'mision' => 'required|string|max:1000',
                'title_suscrib_a' => 'required|string|max:1000',
                'description_suscrib' => 'required|string|max:1000',
                'footer' => 'required|string|max:1000',
                'whatsapp' => 'required|string|max:1000',
                'sinpe' => 'required|string|max:1000'

            ];

            $mensaje = ["required" => 'El :attribute es requerido ' . $id . ' update'];
            $this->validate($request, $campos, $mensaje);
            $tenantinfo = TenantInfo::first();
            if (!isset($tenantinfo)) {
                $this->store($request);
            }
            if ($request->hasFile('logo')) {
                Storage::delete('public/' . $tenantinfo->logo);
                $logo = $request->file('logo')->store('uploads', 'public');
                $tenantinfo->logo = $logo;
            }
            if ($request->hasFile('login_image')) {
                Storage::delete('public/' . $tenantinfo->login_image);
                $login_image = $request->file('login_image')->store('uploads', 'public');
                $tenantinfo->login_image = $login_image;
            }
            $tenantinfo->title = $request->title;
            $tenantinfo->title_discount = $request->title_discount;
            $tenantinfo->title_instagram = $request->title_instagram;
            $tenantinfo->mision = $request->mision;
            $tenantinfo->title_trend = $request->title_trend;
            $tenantinfo->title_suscrib_a = $request->title_suscrib_a;
            $tenantinfo->description_suscrib = $request->description_suscrib;
            $tenantinfo->footer = $request->footer;
            $tenantinfo->whatsapp = $request->whatsapp;
            $tenantinfo->sinpe = $request->sinpe;
            $tenantinfo->count = $request->count;
            $tenantinfo->email = $request->email;
            $tenantinfo->delivery = $request->delivery;
            $tenantinfo->update();
            DB::commit();
            return redirect('/tenant-info')->with(['status' => 'Se ha editado la información del negocio con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/tenant-info')->with(['status' => 'No se pudo guardar la información del negocio', 'icon' => 'error']);
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

            $tenantinfo = TenantInfo::findOrfail($id);
            if (
                Storage::delete('public/' . $tenantinfo->logo)
                && Storage::delete('public/' . $tenantinfo->login_image)
            ) {
                TenantInfo::destroy($id);
            }
            TenantInfo::destroy($id);
            DB::commit();
            return redirect('/tenant-info')->with(['status' => 'Se ha eliminado la información del negocio', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateComp(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $tenantinfo = TenantInfo::first();
            $tenantinfo->manage_size = $request->manage_size ? 1 : 0;
            $tenantinfo->manage_department = $request->manage_department ? 1 : 0;
            $tenantinfo->show_logo = $request->show_logo ? 1 : 0;
            $tenantinfo->show_stock = $request->show_stock ? 1 : 0;
            $tenantinfo->show_trending = $request->show_trending ? 1 : 0;
            $tenantinfo->show_insta = $request->show_insta ? 1 : 0;
            $tenantinfo->show_mision = $request->show_mision ? 1 : 0;
            $tenantinfo->show_cintillo = $request->show_cintillo ? 1 : 0;
            $tenantinfo->custom_size = $request->custom_size ? 1 : 0;
            if (!$request->manage_size) {
                $tenantinfo->custom_size = 0;
            }
            $tenantinfo->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado la visualización de componentes', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la información del negocio', 'icon' => 'error']);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateColor(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $settings = Settings::first();
            $settings->navbar = $request->navbar;
            $settings->navbar_text = $request->navbar_text;
            $settings->title_text = $request->title_text;
            $settings->btn_cart = $request->btn_cart;
            $settings->cart_icon = $request->cart_icon;
            $settings->btn_cart_text = $request->btn_cart_text;
            $settings->footer = $request->footer;
            $settings->footer_text = $request->footer_text;
            $settings->sidebar = $request->sidebar;
            $settings->sidebar_text = $request->sidebar_text;
            $settings->hover = $request->hover;
            $settings->cintillo = $request->cintillo;
            $settings->cintillo_text = $request->cintillo_text;
            $settings->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado los colores de los componentes', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar los colores de los componentes', 'icon' => 'error']);
        }
    }
}
