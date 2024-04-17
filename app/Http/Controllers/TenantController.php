<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
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
}
