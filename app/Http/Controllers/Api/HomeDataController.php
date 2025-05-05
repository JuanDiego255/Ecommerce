<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Categories;

class HomeDataController extends Controller
{
    public function index()
    {
        $tenant = tenancy()->tenant;

        // Si el tenant maneja departamentos, devolvemos departamentos
        if ($tenant->manage_department) {
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
