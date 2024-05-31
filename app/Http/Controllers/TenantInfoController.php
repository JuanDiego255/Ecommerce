<?php

namespace App\Http\Controllers;

use App\Models\ButtonIcon;
use App\Models\Settings;
use App\Models\TenantCarousel;
use App\Models\TenantInfo;
use App\Models\TenantSocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

            $icons = [
                'home', 'tshirt', 'heart', 'spa', 'syringe', 'hands', 'mask', 'car', 'shopping-cart',
                'car-alt','wrench','cogs','paint-brush','flask','lipstick','sparkles',
                'credit-card', 'sign-in', 'map-marker', 'address-book', 'address-card', 'angry', 'arrow-alt-circle-down',
                'arrow-alt-circle-left', 'cut', 'arrow-alt-circle-right', 'arrow-alt-circle-up',
                'bell', 'bell-slash', 'bookmark', 'building', 'calendar',
                'calendar-alt', 'calendar-check', 'calendar-minus', 'calendar-plus',
                'calendar-times', 'caret-square-down', 'caret-square-left',
                'caret-square-right', 'caret-square-up', 'chart-bar', 'check-circle',
                'check-square', 'circle', 'clipboard', 'clock', 'clone',
                'closed-captioning', 'comment', 'comment-alt', 'comment-dots',
                'comments', 'compass', 'copy', 'copyright', 'credit-card',
                'dizzy', 'dot-circle', 'edit', 'envelope', 'envelope-open',
                'eye', 'eye-slash', 'file', 'file-alt', 'file-archive',
                'file-audio', 'file-code', 'file-excel', 'file-image', 'file-pdf',
                'file-powerpoint', 'file-video', 'file-word', 'flag', 'flushed',
                'folder', 'folder-open', 'frown', 'frown-open', 'futbol',
                'gem', 'grimace', 'grin', 'grin-alt', 'grin-beam',
                'grin-beam-sweat', 'grin-hearts', 'grin-squint', 'grin-squint-tears',
                'grin-stars', 'grin-tears', 'grin-tongue', 'grin-tongue-squint',
                'grin-tongue-wink', 'grin-wink', 'hand-lizard', 'hand-paper',
                'hand-peace', 'hand-point-down', 'hand-point-left', 'hand-point-right',
                'hand-point-up', 'hand-pointer', 'hand-rock', 'hand-scissors',
                'hand-spock', 'handshake', 'hdd', 'heart', 'hospital', 'hourglass',
                'id-badge', 'id-card', 'image', 'images', 'keyboard', 'kiss',
                'kiss-beam', 'kiss-wink-heart', 'laugh', 'laugh-beam', 'laugh-squint',
                'laugh-wink', 'lemon', 'life-ring', 'lightbulb', 'list-alt',
                'map', 'meh', 'meh-blank', 'meh-rolling-eyes', 'minus-square',
                'money-bill-alt', 'moon', 'newspaper', 'object-group', 'object-ungroup',
                'paper-plane', 'pause-circle', 'play-circle', 'plus-square',
                'question-circle', 'registered', 'sad-cry', 'sad-tear', 'save',
                'share-square', 'smile', 'smile-beam', 'smile-wink', 'snowflake',
                'square', 'star', 'star-half', 'sticky-note', 'stop-circle',
                'sun', 'surprise', 'thumbs-down', 'thumbs-up', 'times-circle',
                'tired', 'trash-alt', 'user', 'user-circle', 'window-close',
                'window-maximize', 'window-minimize', 'window-restore'
            ];
            return compact('tenant_info', 'tenantcarousel', 'icons');
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
            if ($request->hasFile('logo_ico')) {
                $tenantinfo->logo_ico = $request->file('logo_ico')->store('uploads', 'public');
            }

            $tenantinfo->title = $request->title;
            $tenantinfo->text_cintillo = $request->text_cintillo;
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
            if ($request->hasFile('logo_ico')) {
                Storage::delete('public/' . $tenantinfo->logo_ico);
                $logo_ico = $request->file('logo_ico')->store('uploads', 'public');
                $tenantinfo->logo_ico = $logo_ico;
            }
            $tenantinfo->title = $request->title;
            $tenantinfo->text_cintillo = $request->text_cintillo;
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
            $tenantinfo->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado la visualización de componentes', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la información del negocio'.$th->getMessage(), 'icon' => 'error']);
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
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateIcons(Request $request)
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
    public function saveIcons(Request $request)
    {
        DB::beginTransaction();
        try {
            $new = false;
            $icons = ButtonIcon::first();
            if (!$icons) {
                $icons = new ButtonIcon();
                $new = true;
            }
            $icons->home = $request->home;
            $icons->categories = $request->categories;
            $icons->cart = $request->cart;
            $icons->shopping = $request->shopping;
            $icons->address = $request->address;
            $icons->user = $request->user;
            $icons->services = $request->services;
            $icons->products = $request->products;
            $icons->detail = $request->detail;
            if ($new) {
                $icons->save();
            } else {
                $icons->update();
            }
            DB::commit();
            return redirect()->back()->with(['status' => 'Se han guardado los íconos con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la información de los iconos' . $th->getMessage(), 'icon' => 'error']);
        }
    }
}
