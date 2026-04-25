<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Department;
use App\Models\TenantInfo;
use App\Models\TenantSocialNetwork;
use Illuminate\Support\Facades\DB;

class CatalogApiController extends Controller
{
    // GET /api/catalog/home/{tenant}
    // Single call to render the catalog landing: tenant branding, nav items, featured products.
    public function home($tenant)
    {
        try {
            $tenantInfo = TenantInfo::first();
            $useDepts   = $tenantInfo && (int) $tenantInfo->manage_department === 1;

            if ($useDepts) {
                $navType  = 'departments';
                $navItems = Department::where('department', '!=', 'Default')
                    ->orderBy('department')
                    ->get(['id', 'department as name', 'image']);
            } else {
                $navType     = 'categories';
                $defaultDept = Department::where('department', 'Default')->first();
                $navItems    = $defaultDept
                    ? Categories::where('department_id', $defaultDept->id)
                        ->orderBy('name')
                        ->get(['id', 'name', 'image'])
                    : collect();
            }

            // Featured: trending first, then newest — max 10 active products
            $featured = DB::table('clothing')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')
                         ->whereRaw('product_images.id = (
                             SELECT MIN(pi2.id) FROM product_images pi2
                             WHERE pi2.clothing_id = clothing.id
                         )');
                })
                ->where('clothing.status', 1)
                ->select(
                    'clothing.id',
                    'clothing.name',
                    'clothing.price',
                    'clothing.mayor_price',
                    'clothing.discount',
                    'clothing.manage_stock',
                    DB::raw('COALESCE(
                        (SELECT SUM(vc.stock) FROM variant_combinations vc
                         WHERE vc.clothing_id = clothing.id AND vc.stock >= 0),
                        clothing.stock
                    ) as total_stock'),
                    'product_images.image'
                )
                ->orderByRaw('COALESCE(clothing.trending, 0) DESC, clothing.created_at DESC')
                ->limit(10)
                ->get();

            $social = TenantSocialNetwork::all(['id', 'social_network', 'url']);

            return response()->json([
                'success'     => true,
                'tenant_info' => [
                    'title'              => $tenantInfo->title ?? '',
                    'logo'               => $tenantInfo->logo ?? null,
                    'logo_ico'           => $tenantInfo->logo_ico ?? null,
                    'whatsapp'           => $tenantInfo->whatsapp ?? null,
                    'email'              => $tenantInfo->email ?? null,
                    'footer'             => $tenantInfo->footer ?? null,
                    'about_us'           => $tenantInfo->about_us ?? null,
                    'cintillo'           => (bool) ($tenantInfo->cintillo ?? false),
                    'text_cintillo'      => $tenantInfo->text_cintillo ?? null,
                    'manage_department'  => $useDepts ? 1 : 0,
                ],
                'nav_type'  => $navType,
                'nav_items' => $navItems,
                'featured'  => $featured,
                'social'    => $social,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /api/catalog/product/{id}/{tenant}
    // Full product detail: all images + variant combinations (new system) + legacy stocks fallback.
    public function productDetail($id, $tenant)
    {
        try {
            $product = DB::table('clothing')->where('id', $id)->first();
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
            }

            $images = DB::table('product_images')
                ->where('clothing_id', $id)
                ->orderBy('id')
                ->pluck('image');

            // New combination system
            $rows = DB::table('variant_combinations as vc')
                ->where('vc.clothing_id', $id)
                ->join('variant_combination_values as vcv', 'vcv.combination_id', '=', 'vc.id')
                ->join('attribute_values as v', 'vcv.value_attr', '=', 'v.id')
                ->join('attributes as a', 'vcv.attr_id', '=', 'a.id')
                ->select('vc.id as combination_id', 'a.name as attr', 'v.value as val',
                         'vc.stock', 'vc.price', 'vc.manage_stock')
                ->orderBy('vc.id')
                ->get();

            $variants = $rows->groupBy('combination_id')->map(function ($parts) {
                $label = $parts->map(fn($r) => $r->attr . ': ' . $r->val)->implode(' / ');
                $first = $parts->first();
                return [
                    'combination_id' => $first->combination_id,
                    'label'          => $label,
                    'stock'          => (int) $first->stock,
                    'price'          => (float) $first->price,
                    'manage_stock'   => (int) $first->manage_stock,
                ];
            })->values();

            // Legacy fallback
            if ($variants->isEmpty()) {
                $legacy = DB::table('stocks')
                    ->where('clothing_id', $id)
                    ->join('attribute_values', 'stocks.value_attr', '=', 'attribute_values.id')
                    ->join('attributes', 'stocks.attr_id', '=', 'attributes.id')
                    ->select('stocks.id as combination_id',
                             'attributes.name as attr', 'attribute_values.value as val',
                             'stocks.stock', 'stocks.price', DB::raw('1 as manage_stock'))
                    ->get();
                $variants = $legacy->map(fn($r) => [
                    'combination_id' => $r->combination_id,
                    'label'          => $r->attr . ': ' . $r->val,
                    'stock'          => (int) $r->stock,
                    'price'          => (float) $r->price,
                    'manage_stock'   => 1,
                ])->values();
            }

            $categories = DB::table('categories')
                ->join('pivot_clothing_categories', 'categories.id', '=', 'pivot_clothing_categories.category_id')
                ->where('pivot_clothing_categories.clothing_id', $id)
                ->pluck('categories.name');

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'           => $product->id,
                    'name'         => $product->name,
                    'code'         => $product->code,
                    'description'  => $product->description,
                    'price'        => (float) $product->price,
                    'mayor_price'  => $product->mayor_price ? (float) $product->mayor_price : null,
                    'discount'     => (int) ($product->discount ?? 0),
                    'manage_stock' => (int) $product->manage_stock,
                    'stock'        => (int) $product->stock,
                    'images'       => $images,
                    'variants'     => $variants,
                    'categories'   => $categories,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /api/catalog/attributes/{categoryId}/{tenant}
    // Returns attribute types with all their values so Flutter can build filter chips.
    public function attributesByCategory($categoryId, $tenant)
    {
        try {
            $attrIds = DB::table('attributes as a')
                ->join('stocks as s', 's.attr_id', '=', 'a.id')
                ->join('clothing as c', 's.clothing_id', '=', 'c.id')
                ->join('pivot_clothing_categories as pcc', 'c.id', '=', 'pcc.clothing_id')
                ->where('pcc.category_id', $categoryId)
                ->where('c.status', 1)
                ->distinct()
                ->select('a.id', 'a.name')
                ->get();

            $result = $attrIds->map(function ($attr) {
                $values = DB::table('attribute_values')
                    ->where('attribute_id', $attr->id)
                    ->select('id', 'value')
                    ->orderBy('value')
                    ->get();
                return ['id' => $attr->id, 'name' => $attr->name, 'values' => $values];
            });

            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
