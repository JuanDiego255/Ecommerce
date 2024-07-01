<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Department;
use App\Models\Settings;
use App\Models\Size;
use App\Models\TenantInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::beginTransaction();
        try {
            $tenantinfo =  new TenantInfo();
            $tenantinfo->logo = "n/a";
            $tenantinfo->login_image = "n/a";
            $tenantinfo->title = "NUEVO";
            $tenantinfo->title_discount = "";
            $tenantinfo->title_instagram = "";
            $tenantinfo->mision = "";
            $tenantinfo->title_trend = "";
            $tenantinfo->title_suscrib_a = "";
            $tenantinfo->description_suscrib = "";
            $tenantinfo->footer = "";
            $tenantinfo->whatsapp = "88888888";
            $tenantinfo->sinpe = "88888888";
            $tenantinfo->count = "0";
            $tenantinfo->email = "usuario@gmail.com";
            $tenantinfo->delivery = "0";
            $tenantinfo->manage_size = 1;
            $tenantinfo->manage_department = 0;
            $tenantinfo->show_stock = 1;
            $tenantinfo->license = 1;
            $tenantinfo->show_trending = 1;
            $tenantinfo->show_insta = 1;
            $tenantinfo->show_mision = 1;
            $tenantinfo->show_cintillo = 1;
            $tenantinfo->custom_size = 0;
            $tenantinfo->save();

            $settings = new Settings();
            $settings->navbar = "#0a0a0a";
            $settings->navbar_text = "#fcfcfc";
            $settings->title_text = "#0a0a0a";
            $settings->btn_cart = "#0a0a0a";
            $settings->cart_icon = "#fcfcfc";
            $settings->btn_cart_text = "#0a0a0a";
            $settings->footer = "#fcfcfc";
            $settings->footer_text = "#0a0a0a";
            $settings->sidebar = "#0a0a0a";
            $settings->sidebar_text = "#fcfcfc";
            $settings->hover = "#0a0a0a";
            $settings->cintillo = "#4f7dba";
            $settings->cintillo_text = "#0a0a0a";
            $settings->save();

            $new_department = new Department();
            $new_department->department = "Default";
            $new_department->save();

            $attr = new Attribute();
            $attr->name = "Stock";
            $attr->type = "0";
            $attr->save();
            DB::commit();
            return true;
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }
}
