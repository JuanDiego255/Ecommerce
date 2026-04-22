<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\PivotClothingCategory;
use App\Models\ProductImage;
use App\Models\TenantInfo;
use App\Models\VariantCombination;
use App\Models\VariantCombinationValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductApiController extends Controller
{
    // GET /api/admin/product/{id}
    public function show($id)
    {
        try {
            $product = ClothingCategory::findOrFail($id);

            $categoryIds = PivotClothingCategory::where('clothing_id', $id)
                ->pluck('category_id')
                ->toArray();

            $images = ProductImage::where('clothing_id', $id)
                ->orderBy('id')
                ->get()
                ->map(fn($img) => [
                    'id'  => $img->id,
                    'url' => 'https://' . request()->getHost() . '/file/' . $img->image,
                    'path' => $img->image,
                ]);

            $combinations = DB::table('variant_combinations as vc')
                ->where('vc.clothing_id', $id)
                ->join('variant_combination_values as vcv', 'vcv.combination_id', '=', 'vc.id')
                ->join('attribute_values as av', 'vcv.value_attr', '=', 'av.id')
                ->join('attributes as a', 'vcv.attr_id', '=', 'a.id')
                ->select(
                    'vc.id as combination_id',
                    'vc.price',
                    'vc.stock',
                    'vc.manage_stock',
                    'a.name as attr_name',
                    'av.value as attr_value',
                    'av.id as value_id',
                    'vcv.attr_id'
                )
                ->orderBy('vc.id')
                ->get()
                ->groupBy('combination_id')
                ->map(function ($rows) {
                    $first = $rows->first();
                    return [
                        'combination_id' => $first->combination_id,
                        'label' => $rows->map(fn($r) => $r->attr_name . ': ' . $r->attr_value)->implode(' / '),
                        'price' => (float) $first->price,
                        'stock' => $first->stock,
                        'manage_stock' => $first->manage_stock,
                        'values' => $rows->map(fn($r) => [
                            'attr_id'  => $r->attr_id,
                            'value_id' => $r->value_id,
                        ])->values(),
                    ];
                })
                ->values();

            return response()->json([
                'success'      => true,
                'product'      => $product,
                'category_ids' => $categoryIds,
                'images'       => $images,
                'combinations' => $combinations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener producto: ' . $e->getMessage(),
            ], 500);
        }
    }

    // POST /api/admin/products
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tenantinfo = TenantInfo::first();

            $clothing = new ClothingCategory();
            $clothing->name         = $request->name;
            $clothing->code         = $request->code ?: $this->generateSku();
            $clothing->description  = $request->description ?? '';
            $clothing->price        = $request->price ?? 0;
            $clothing->stock        = $request->stock ?? 0;
            $clothing->status       = 1;
            $clothing->trending     = $request->filled('trending') ? 1 : 0;
            $clothing->manage_stock = $request->manage_stock ? 1 : 0;
            $clothing->discount     = $request->discount ?: null;
            $clothing->meta_keywords = $request->meta_keywords ?? null;
            if ($tenantinfo && $tenantinfo->tenant === 'torres') {
                $clothing->mayor_price = $request->mayor_price;
            }
            $clothing->save();

            $this->syncCategories($clothing->id, $request->input('category_ids', '[]'));
            $this->saveImages($clothing->id, $request);
            $this->syncCombos($clothing->id, $request->input('combos', '[]'), $request->manage_stock);

            DB::commit();
            return response()->json([
                'success'    => true,
                'message'    => 'Producto creado exitosamente',
                'product_id' => $clothing->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear producto: ' . $e->getMessage(),
            ], 500);
        }
    }

    // POST /api/admin/products/{id}  (PUT via POST for multipart compatibility)
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $tenantinfo = TenantInfo::first();
            $clothing = ClothingCategory::findOrFail($id);

            $clothing->name         = $request->name;
            $clothing->code         = $request->code;
            $clothing->description  = $request->description ?? '';
            $clothing->price        = $request->price ?? 0;
            $clothing->stock        = $request->stock ?? 0;
            $clothing->trending     = $request->filled('trending') ? 1 : 0;
            $clothing->manage_stock = $request->manage_stock ? 1 : 0;
            $clothing->discount     = $request->discount ?: null;
            $clothing->meta_keywords = $request->meta_keywords ?? null;
            if ($tenantinfo && $tenantinfo->tenant === 'torres') {
                $clothing->mayor_price = $request->mayor_price;
            }
            $clothing->save();

            $this->syncCategories($id, $request->input('category_ids', '[]'));

            if ($request->hasFile('images')) {
                $old = ProductImage::where('clothing_id', $id)->get();
                foreach ($old as $img) {
                    Storage::delete('public/' . $img->image);
                }
                ProductImage::where('clothing_id', $id)->delete();
                $this->saveImages($id, $request);
            }

            $this->syncCombos($id, $request->input('combos', '[]'), $request->manage_stock);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Producto actualizado exitosamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage(),
            ], 500);
        }
    }

    // DELETE /api/admin/products/{id}
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $images = ProductImage::where('clothing_id', $id)->get();
            foreach ($images as $img) {
                Storage::delete('public/' . $img->image);
            }
            ProductImage::where('clothing_id', $id)->delete();
            PivotClothingCategory::where('clothing_id', $id)->delete();
            VariantCombination::where('clothing_id', $id)->each(function ($vc) {
                VariantCombinationValue::where('combination_id', $vc->id)->delete();
            });
            VariantCombination::where('clothing_id', $id)->delete();
            ClothingCategory::destroy($id);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Producto eliminado']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /api/admin/categories-all
    public function allCategories()
    {
        try {
            $cats = Categories::orderBy('name')->get(['id', 'name', 'department_id']);
            return response()->json(['success' => true, 'data' => $cats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /api/admin/attributes-all
    public function allAttributes()
    {
        try {
            $attrs = Attribute::where('name', '!=', 'Stock')
                ->orderBy('name')
                ->get();

            $result = $attrs->map(fn($a) => [
                'id'     => $a->id,
                'name'   => $a->name,
                'main'   => $a->main,
                'values' => AttributeValue::where('attribute_id', $a->id)
                    ->orderBy('value')
                    ->get(['id', 'value']),
            ]);

            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // POST /api/admin/attributes
    public function storeAttribute(Request $request)
    {
        try {
            $request->validate(['name' => 'required|string|max:100']);
            $attr = new Attribute();
            $attr->name = $request->name;
            $attr->type = 0;
            $attr->main = 0;
            $attr->save();
            return response()->json([
                'success' => true,
                'data'    => ['id' => $attr->id, 'name' => $attr->name, 'main' => 0, 'values' => []],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // POST /api/admin/attributes/{id}/values
    public function storeAttributeValue(Request $request, $id)
    {
        try {
            $request->validate(['value' => 'required|string|max:1000']);
            $val = new AttributeValue();
            $val->attribute_id = $id;
            $val->value        = $request->value;
            $val->save();
            return response()->json([
                'success' => true,
                'data'    => ['id' => $val->id, 'value' => $val->value, 'attr_id' => (int) $id],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // PUT /api/admin/attributes/{id}/values/{valueId}
    public function updateAttributeValue(Request $request, $id, $valueId)
    {
        try {
            $request->validate(['value' => 'required|string|max:1000']);
            $val = AttributeValue::findOrFail($valueId);
            $val->value = $request->value;
            $val->save();
            return response()->json(['success' => true, 'data' => ['id' => $val->id, 'value' => $val->value]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/admin/attributes/{id}
    public function destroyAttribute($id)
    {
        try {
            AttributeValue::where('attribute_id', $id)->delete();
            Attribute::destroy($id);
            return response()->json(['success' => true, 'message' => 'Atributo eliminado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/admin/attribute-values/{id}
    public function destroyAttributeValue($id)
    {
        try {
            AttributeValue::destroy($id);
            return response()->json(['success' => true, 'message' => 'Valor eliminado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function syncCategories($clothingId, $raw)
    {
        $ids = is_array($raw) ? $raw : (json_decode($raw, true) ?? []);
        $ids = array_map('intval', $ids);

        PivotClothingCategory::where('clothing_id', $clothingId)
            ->whereNotIn('category_id', $ids)
            ->delete();

        foreach ($ids as $catId) {
            if (!PivotClothingCategory::where('clothing_id', $clothingId)->where('category_id', $catId)->exists()) {
                $p = new PivotClothingCategory();
                $p->clothing_id  = $clothingId;
                $p->category_id  = $catId;
                $p->save();
            }
        }
    }

    private function saveImages($clothingId, Request $request)
    {
        if (!$request->hasFile('images')) return;
        foreach ($request->file('images') as $image) {
            $img = new ProductImage();
            $img->clothing_id = $clothingId;
            $img->image       = $image->store('uploads', 'public');
            $img->save();
        }
    }

    private function syncCombos($clothingId, $raw, $manageStock)
    {
        $combos = is_array($raw) ? $raw : (json_decode($raw, true) ?? []);
        if (empty($combos)) return;

        $submittedIds = collect($combos)
            ->pluck('combination_id')
            ->filter(fn($v) => $v !== '' && $v !== null)
            ->map('intval')
            ->toArray();

        // Delete removed combinations
        $toDelete = VariantCombination::where('clothing_id', $clothingId)
            ->when(!empty($submittedIds), fn($q) => $q->whereNotIn('id', $submittedIds))
            ->get();
        foreach ($toDelete as $vc) {
            VariantCombinationValue::where('combination_id', $vc->id)->delete();
            $vc->delete();
        }

        foreach ($combos as $combo) {
            $combo         = is_array($combo) ? $combo : (array) $combo;
            $combinationId = $combo['combination_id'] ?? null;
            $valueIds      = $combo['values'] ?? [];
            $price         = (float) ($combo['price'] ?? 0);
            $stock         = (int) ($combo['stock'] ?? 0);

            if ($combinationId) {
                $vc = VariantCombination::find($combinationId);
                if ($vc) {
                    $vc->price        = $price;
                    $vc->stock        = $manageStock ? $stock : -1;
                    $vc->manage_stock = $manageStock ? 1 : 0;
                    $vc->save();
                }
            } else {
                $vc = new VariantCombination();
                $vc->clothing_id  = $clothingId;
                $vc->price        = $price;
                $vc->stock        = $manageStock ? $stock : -1;
                $vc->manage_stock = $manageStock ? 1 : 0;
                $vc->save();

                foreach ($valueIds as $valId) {
                    $av = AttributeValue::find($valId);
                    if ($av) {
                        $vcv = new VariantCombinationValue();
                        $vcv->combination_id = $vc->id;
                        $vcv->attr_id        = $av->attribute_id;
                        $vcv->value_attr     = $valId;
                        $vcv->save();
                    }
                }
            }
        }
    }

    private function generateSku()
    {
        do {
            $sku = 'P' . str_pad(random_int(0, 9999999999999), 13, '0', STR_PAD_LEFT);
        } while (ClothingCategory::where('code', $sku)->exists());
        return $sku;
    }
}
