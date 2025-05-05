<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Categories;
use App\Models\Tenant;
use App\Models\TenantInfo;

class HomeDataController extends Controller
{
    public function index($tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        $tenantinfo = TenantInfo::first();

        // Si el tenant maneja departamentos, devolvemos departamentos
        if ($tenantinfo->manage_department == 1) {
            $departments = Department::where('department', '!=', 'Default')
                ->orderBy('department', 'asc')
                ->get(['id', 'department as name', 'image']);

            return response()->json([
                'type' => 'departments',
                'data' => $departments
            ]);
        }

        // Si no maneja departamentos, devolvemos categorías
        $defaultDepartment = Department::where('department', 'Default')->first();

        $categories = Categories::where('department_id', $defaultDepartment->id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'image']);

        return response()->json([
            'type' => 'categories',
            'data' => $categories
        ]);
    }
}
