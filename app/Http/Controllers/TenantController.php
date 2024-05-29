<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tenants = Tenant::where('id','!=','main')->get();

        $tenants = $tenants->map(function ($tenant) {
            $tenant_info = $this->getData($tenant->id);
            $tenant->license = $tenant_info->license;
            $tenant->manage_size = $tenant_info->manage_size;
            $tenant->manage_department = $tenant_info->manage_department;
            return $tenant;
        });
        return view('admin.tenant.index', compact('tenants'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage($tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        $users = User::all();
        tenancy()->end();
        return view('admin.tenant.manage', compact('users', 'tenant'));
    }
    public function isAdmin($tenant, Request $request)
    {
        //
        DB::beginTransaction();        
        try {
            $tenants = Tenant::where('id', $tenant)->first();
            tenancy()->initialize($tenants);
            if ($request->role_as == "1") {
                User::where('id', $request->id)->update(['role_as' => 1]);
            } else {
                User::where('id', $request->id)->update(['role_as' => 0]);
            }

            DB::commit();
            tenancy()->end();
            return redirect()->back()->with(['status' => 'Se cambio el estado (Es Admin) para este usuario', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function frontend()
    {       
        return view('frontend.central.index');
    }
    public function getData($tenant){
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        $tenant_info = TenantInfo::first();       
        tenancy()->end();
        return $tenant_info;
    }

    public function isLicense($tenant, Request $request)
    {
        //
        DB::beginTransaction();      
        try {
            $tenants = Tenant::where('id', $tenant)->first();
            tenancy()->initialize($tenants);
            if ($request->license == "1") {
                TenantInfo::where('tenant', $tenant)->update(['license' => 1]);
            } else {
                TenantInfo::where('tenant', $tenant)->update(['license' => 0]);
            }

            DB::commit();
            tenancy()->end();
            return redirect()->back()->with(['status' => 'Se cambio el estado de la licencia para este inquilino', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    public function store(Request $request)
    {
        //
        DB::beginTransaction();      
        try {
            $datos = [
                "tenancy_db_name" => "safewors_".$request->tenant,
                "tenancy_db_password" => "UYHkOYFXReJ4aDcJ",
                "tenancy_db_username" => "safewors"
            ];
            $new_tenant = new Tenant();
            $new_tenant->id = $request->tenant;
            $new_tenant->data = $datos;
            $new_tenant->save();
            $id = $new_tenant->id;
            $new_domain = new Domain();
            $new_domain->domain = $id.".safeworsolutions.com";
            $new_domain->tenant_id = $id;
            $new_domain->save();
            
            Artisan::call('tenants:migrate');
            $tenants = Tenant::where('id', $id)->first();           
            tenancy()->initialize($tenants);
            Artisan::call('db:seed');
            tenancy()->end();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se agregó un nuevo inquilino', 'icon' => 'success']);
        } catch (\Exception $th) {
            dd($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with(['status' => $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function generateSitemap(){
        try {
            Artisan::call('tenants:sitemap:generate ');
            return redirect()->back()->with(['status' => 'Se crearon los sitemaps con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            return redirect()->back()->with(['status' => 'Hubo un error al crear los sitemaps', 'icon' => 'error']);
        }       
    }
}
