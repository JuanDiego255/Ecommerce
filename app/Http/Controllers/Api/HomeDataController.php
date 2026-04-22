<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Categories;
use App\Models\Tenant;
use App\Models\TenantInfo;
use Illuminate\Support\Facades\DB;

class HomeDataController extends Controller
{
    //Método que devuelve los departmanentos o categorías API
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
    //Método que devuelve los departmanentos o categorías API
    public function getTenantInfo($tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        $tenantinfo = TenantInfo::first();
        
        return response()->json([
            'data' => $tenantinfo
        ]);
    }
    //Método que devuelve los productos por categoría
    public function apiIndexByCategory($id, $tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        $statusFilter = request()->get('status', 2); // 2 = Todos

        $products = DB::table('clothing')
            ->join('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
            ->join('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
            ->leftJoin('stocks', 'clothing.id', '=', 'stocks.clothing_id')
            ->leftJoin('attributes', 'stocks.attr_id', '=', 'attributes.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('clothing.id', '=', 'product_images.clothing_id')
                    ->whereRaw('product_images.id = (
                    SELECT MIN(id) FROM product_images
                    WHERE product_images.clothing_id = clothing.id
                )');
            })
            ->where('pivot_clothing_categories.category_id', $id);

        if ($statusFilter != 2) {
            $products->where('clothing.status', $statusFilter);
        }

        $products = $products
            ->select(
                'clothing.id',
                'clothing.name',
                'clothing.code',
                'clothing.description',
                'clothing.price',
                'clothing.mayor_price',
                'clothing.discount',
                'clothing.manage_stock',
                DB::raw('COALESCE((SELECT SUM(vc.stock) FROM variant_combinations vc WHERE vc.clothing_id = clothing.id AND vc.stock >= 0), SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END)) as total_stock'),
                DB::raw('GROUP_CONCAT(DISTINCT COALESCE(attributes.name, "")) AS available_attr'),
                'product_images.image as image'
            )
            ->groupBy(
                'clothing.id',
                'clothing.name',
                'clothing.code',
                'clothing.description',
                'clothing.price',
                'clothing.mayor_price',
                'clothing.discount',
                'clothing.manage_stock',
                'product_images.image'
            )
            ->orderBy('clothing.name', 'asc')
            ->get();
        tenancy()->end();
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    //Método que devuelve las variantes/combinaciones de un producto
    public function apiProductVariants($id, $tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        if (!$tenants) {
            return response()->json(['success' => false, 'message' => 'Tenant no encontrado'], 404);
        }
        tenancy()->initialize($tenants);

        $rows = DB::table('variant_combinations as vc')
            ->where('vc.clothing_id', $id)
            ->join('variant_combination_values as vcv', 'vcv.combination_id', '=', 'vc.id')
            ->join('attribute_values as v', 'vcv.value_attr', '=', 'v.id')
            ->join('attributes as a', 'vcv.attr_id', '=', 'a.id')
            ->select('vc.id as combination_id', 'a.name as attr', 'v.value as val', 'vc.stock', 'vc.price', 'vc.manage_stock')
            ->orderBy('vc.id')->get();

        $combinations = $rows->groupBy('combination_id')->map(function ($parts) {
            $label = $parts->map(fn($r) => $r->attr . ': ' . $r->val)->implode(' / ');
            $first = $parts->first();
            return [
                'combination_id' => $first->combination_id,
                'label'          => $label,
                'stock'          => $first->stock,
                'price'          => (float) $first->price,
                'manage_stock'   => $first->manage_stock,
            ];
        })->values();

        tenancy()->end();
        return response()->json(['success' => true, 'data' => $combinations]);
    }
    //Método que devuelve las categorías por departamento
    public function apiCategoriesByDepartment($id, $tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        if ($id == null) {
            $department = Department::where('department', 'Default')->first();
            $department_id = $department->id;
        } else {
            $department = Department::where('id', $id)->first();
            $department_id = $department->id;
        }

        $categories = Categories::where('department_id', $department_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'image']);
        tenancy()->end();
        return response()->json([
            'success' => true,
            'department' => $department->department,
            'data' => $categories
        ]);
    }
}
