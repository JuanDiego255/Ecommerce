<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            dd($request);
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
    public function manageSize($tenant, Request $request)
    {
        //
        DB::beginTransaction();      
        try {
            $tenants = Tenant::where('id', $tenant)->first();
            tenancy()->initialize($tenants);
            if ($request->manage_size == "1") {
                TenantInfo::where('tenant', $tenant)->update(['manage_size' => 1]);
            } else {
                TenantInfo::where('tenant', $tenant)->update(['manage_size' => 0]);
            }

            DB::commit();
            tenancy()->end();
            return redirect()->back()->with(['status' => 'Se cambio el estado del manejo de tallas para este inquilino', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    public function manageDepartment($tenant, Request $request)
    {
        //
        DB::beginTransaction();      
        try {
            $tenants = Tenant::where('id', $tenant)->first();
            tenancy()->initialize($tenants);
            if ($request->manage_department == "1") {
                TenantInfo::where('tenant', $tenant)->update(['manage_department' => 1]);
            } else {
                TenantInfo::where('tenant', $tenant)->update(['manage_department' => 0]);
            }

            DB::commit();
            tenancy()->end();
            return redirect()->back()->with(['status' => 'Se cambio el estado del manejo de departamentos para este inquilino', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
