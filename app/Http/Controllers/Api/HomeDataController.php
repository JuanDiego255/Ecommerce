<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Categories;
use App\Models\TenantInfo;
use Illuminate\Support\Facades\DB;

class HomeDataController extends Controller
{
    public function index($tenant)
    {
        try {
            $tenantinfo = TenantInfo::first();

            if ($tenantinfo && $tenantinfo->manage_department == 1) {
                $departments = Department::where('department', '!=', 'Default')
                    ->orderBy('department', 'asc')
                    ->get(['id', 'department as name', 'image']);

                return response()->json([
                    'type' => 'departments',
                    'data' => $departments,
                ]);
            }

            $defaultDepartment = Department::where('department', 'Default')->first();
            if (!$defaultDepartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Departamento por defecto no encontrado en la base de datos',
                ], 500);
            }

            $categories = Categories::where('department_id', $defaultDepartment->id)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'image']);

            return response()->json([
                'type' => 'categories',
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del catálogo: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getTenantInfo($tenant)
    {
        try {
            $tenantinfo = TenantInfo::first();

            return response()->json([
                'data' => $tenantinfo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener info del tenant: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiIndexByCategory($id, $tenant)
    {
        try {
            $statusFilter = request()->get('status', 2);
            $search       = request()->get('search', '');
            $page         = (int) request()->get('page', 1);
            $perPage      = (int) request()->get('per_page', 15);

            $query = DB::table('clothing')
                ->join('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->join('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                ->leftJoin('stocks', 'clothing.id', '=', 'stocks.clothing_id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')
                        ->whereRaw('product_images.id = (
                        SELECT MIN(id) FROM product_images
                        WHERE product_images.clothing_id = clothing.id
                    )');
                })
                ->where('pivot_clothing_categories.category_id', $id);

            if ($statusFilter != 2) {
                $query->where('clothing.status', $statusFilter);
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('clothing.name', 'like', "%{$search}%")
                      ->orWhere('clothing.code', 'like', "%{$search}%");
                });
            }

            $attrValues = array_values(array_filter(explode(',', request()->get('attr_values', ''))));
            if (!empty($attrValues)) {
                $query->where(function ($filterQ) use ($attrValues) {
                    // Legacy stocks
                    $filterQ->whereExists(function ($q) use ($attrValues) {
                        $q->select(DB::raw(1))
                          ->from('stocks as s_filter')
                          ->join('attribute_values as av_f', 's_filter.value_attr', '=', 'av_f.id')
                          ->whereColumn('s_filter.clothing_id', 'clothing.id')
                          ->whereIn('av_f.value', $attrValues);
                    })
                    // New combination system (in-stock only)
                    ->orWhereExists(function ($q) use ($attrValues) {
                        $q->select(DB::raw(1))
                          ->from('variant_combinations as vc_f')
                          ->join('variant_combination_values as vcv_f', 'vcv_f.combination_id', '=', 'vc_f.id')
                          ->join('attribute_values as av_f2', 'vcv_f.value_attr', '=', 'av_f2.id')
                          ->whereColumn('vc_f.clothing_id', 'clothing.id')
                          ->whereIn('av_f2.value', $attrValues)
                          ->where(function ($sub) {
                              $sub->where('vc_f.stock', '>', 0)->orWhere('vc_f.manage_stock', 0);
                          });
                    });
                });
            }

            $query->select(
                    'clothing.id',
                    'clothing.name',
                    'clothing.code',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                    'clothing.discount',
                    'clothing.manage_stock',
                    DB::raw('COALESCE((SELECT SUM(vc.stock) FROM variant_combinations vc WHERE vc.clothing_id = clothing.id AND vc.stock >= 0), SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END)) as total_stock'),
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
                ->orderBy('clothing.name', 'asc');

            $total   = DB::table(DB::raw("({$query->toSql()}) as sub"))
                ->mergeBindings($query)
                ->count();

            $products = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            // Enrich with in-stock attr groups (supports both legacy stocks and new variant_combinations)
            $attrRows = $this->getInStockAttrGroups($products->pluck('id')->toArray());
            $products = $products->map(function ($p) use ($attrRows) {
                $rows = $attrRows->get($p->id, collect());
                $p->available_attr_groups = $rows->map(fn($r) => $r->attr_name . '|' . $r->value)->unique()->join(',');
                return $p;
            });

            return response()->json([
                'success' => true,
                'data'    => $products,
                'pagination' => [
                    'current_page' => $page,
                    'per_page'     => $perPage,
                    'total'        => $total,
                    'last_page'    => (int) ceil($total / $perPage),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiProductVariants($id, $tenant)
    {
        try {
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

            return response()->json(['success' => true, 'data' => $combinations]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener variantes: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function globalSearch($tenant)
    {
        try {
            $q       = trim(request()->get('q', ''));
            $page    = (int) request()->get('page', 1);
            $perPage = (int) request()->get('per_page', 20);

            if ($q === '') {
                return response()->json([
                    'success'    => true,
                    'data'       => [],
                    'pagination' => ['current_page' => 1, 'per_page' => $perPage, 'total' => 0, 'last_page' => 1],
                ]);
            }

            $query = DB::table('clothing')
                ->leftJoin('stocks', 'clothing.id', '=', 'stocks.clothing_id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')
                        ->whereRaw('product_images.id = (SELECT MIN(id) FROM product_images WHERE product_images.clothing_id = clothing.id)');
                })
                ->where('clothing.status', 1)
                ->where(function ($sq) use ($q) {
                    $sq->where('clothing.name', 'like', "%{$q}%")
                       ->orWhere('clothing.code', 'like', "%{$q}%");
                })
                ->select(
                    'clothing.id', 'clothing.name', 'clothing.code', 'clothing.description',
                    'clothing.price', 'clothing.mayor_price', 'clothing.discount', 'clothing.manage_stock',
                    DB::raw('COALESCE((SELECT SUM(vc.stock) FROM variant_combinations vc WHERE vc.clothing_id = clothing.id AND vc.stock >= 0), SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END)) as total_stock'),
                    'product_images.image as image'
                )
                ->groupBy('clothing.id', 'clothing.name', 'clothing.code', 'clothing.description',
                          'clothing.price', 'clothing.mayor_price', 'clothing.discount', 'clothing.manage_stock',
                          'product_images.image')
                ->orderBy('clothing.name');

            $total    = DB::table(DB::raw("({$query->toSql()}) as sub"))->mergeBindings($query)->count();
            $products = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            // Enrich with in-stock attr groups
            $attrRows = $this->getInStockAttrGroups($products->pluck('id')->toArray());
            $products = $products->map(function ($p) use ($attrRows) {
                $rows = $attrRows->get($p->id, collect());
                $p->available_attr_groups = $rows->map(fn($r) => $r->attr_name . '|' . $r->value)->unique()->join(',');
                return $p;
            });

            return response()->json([
                'success'    => true,
                'data'       => $products,
                'pagination' => [
                    'current_page' => $page,
                    'per_page'     => $perPage,
                    'total'        => $total,
                    'last_page'    => (int) ceil($total / $perPage) ?: 1,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Returns in-stock attribute name+value pairs grouped by clothing_id.
     * Prefers new variant_combinations; falls back to legacy stocks for products without combos.
     */
    protected function getInStockAttrGroups(array $ids): \Illuminate\Support\Collection
    {
        if (empty($ids)) return collect();

        $comboRows = DB::table('variant_combinations as vc')
            ->join('variant_combination_values as vcv', 'vcv.combination_id', '=', 'vc.id')
            ->join('attributes as a', 'vcv.attr_id', '=', 'a.id')
            ->join('attribute_values as av', 'vcv.value_attr', '=', 'av.id')
            ->whereIn('vc.clothing_id', $ids)
            ->where(function ($q) {
                $q->where('vc.stock', '>', 0)->orWhere('vc.manage_stock', 0);
            })
            ->select('vc.clothing_id', 'a.name as attr_name', 'av.value')
            ->distinct()->get()->groupBy('clothing_id');

        $needsLegacy = collect($ids)->reject(fn($id) => $comboRows->has($id))->values()->toArray();
        $legacyRows  = !empty($needsLegacy)
            ? DB::table('stocks as s')
                ->join('attributes as a', 's.attr_id', '=', 'a.id')
                ->join('attribute_values as av', 's.value_attr', '=', 'av.id')
                ->whereIn('s.clothing_id', $needsLegacy)
                ->where(function ($q) { $q->where('s.stock', '>', 0)->orWhere('s.stock', -1); })
                ->select('s.clothing_id', 'a.name as attr_name', 'av.value')
                ->distinct()->get()->groupBy('clothing_id')
            : collect();

        return $comboRows->union($legacyRows);
    }

    public function apiCategoriesByDepartment($id, $tenant)
    {
        try {
            if ($id == null) {
                $department = Department::where('department', 'Default')->first();
            } else {
                $department = Department::where('id', $id)->first();
            }

            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Departamento no encontrado',
                ], 404);
            }

            $categories = Categories::where('department_id', $department->id)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'image']);

            return response()->json([
                'success' => true,
                'department' => $department->department,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener categorías: ' . $e->getMessage(),
            ], 500);
        }
    }
}
