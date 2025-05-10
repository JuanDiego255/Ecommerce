<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\ClothingCategory;
use App\Models\Favorite;
use App\Models\ProductImage;
use App\Models\TenantInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $user_id = $request->user_id;
            $clothing_id = $request->clothing_id;
            $attributes = json_decode($request->input('attributes'), true);
            foreach ($attributes as $attr) {
                [$value_attr, $attr_id_val, $cloth_id] = explode('-', $attr);
            }
            // Verificar si ya existe el favorito
            $favorite = Favorite::where('user_id', $user_id)
                ->where('clothing_id', $clothing_id)
                ->first();

            if ($favorite) {
                // Si existe, eliminarlo
                $favorite->delete();
                $favNumber = count(Favorite::where('user_id', $user_id)->get());
                return response()->json(['status' => 'removed', 'favNumber' => $favNumber]);
            } else {
                // Si no existe, agregarlo
                Favorite::create([
                    'user_id' => $user_id,
                    'clothing_id' => $clothing_id,
                    'category_id' => $request->category_id,
                    'attr_id' => $attr_id_val ?? null,
                    'value_attr' => $value_attr ?? null
                ]);
                $favNumber = count(Favorite::where('user_id', $user_id)->get());
                return response()->json(['status' => 'added', 'favNumber' => $favNumber]);
            }
        } catch (\Exception $th) {
            return response()->json(['status' => 'falló: ' . $th->getMessage()]);
        }
    }
    public function checkCode($id)
    {
        try {
            $tenantinfo = TenantInfo::first();
            $check_user = User::where('code_love', $id)->exists();
            if (!$check_user) {
                return redirect()
                    ->back()
                    ->with(['status' => 'No hay ningún usuario vinculado con el código ingresado', 'icon' => 'warning']);
            }
            $user = User::where('code_love', $id)->first();
            $clothings = ClothingCategory::where('clothing.status', 1)
                ->where('favorites.user_id', $user->id)
                ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')

                ->join('favorites', 'clothing.id', '=', 'favorites.clothing_id')
                ->leftJoin('categories', 'favorites.category_id', '=', 'categories.id')
                ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')
                        ->whereRaw('product_images.id = (
                    SELECT MIN(id) FROM product_images 
                    WHERE product_images.clothing_id = clothing.id
                )');
                })
                ->leftJoin('attribute_values', 'favorites.value_attr', '=', 'attribute_values.id')
                ->select(
                    DB::raw('GROUP_CONCAT(DISTINCT categories.name SEPARATOR ", ") as categories'), // Agrupar nombres de categorías
                    DB::raw('GROUP_CONCAT(DISTINCT categories.id SEPARATOR ", ") as category_ids'), // Agrupar IDs de categorías
                    'clothing.id as id',
                    'categories.id as category_id',
                    'categories.name as category_name',
                    'clothing.name as name',
                    'attribute_values.value',
                    'clothing.casa as casa',
                    'clothing.can_buy as can_buy',
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    'product_images.image as image',
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy('clothing.id', 'categories.id', 'categories.name', 'attribute_values.value', 'clothing.can_buy', 'clothing.casa', 'clothing.mayor_price', 'clothing.discount', 'clothing.name', 'clothing.description', 'clothing.price', 'product_images.image')
                ->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
                ->orderBy('clothing.casa', 'asc')
                ->orderBy('clothing.name', 'asc')
                ->simplePaginate(50);
            if (count($clothings) == 0) {
                return redirect()
                    ->back()
                    ->with(['status' => 'No hay productos favoritos en la lista del usuario', 'icon' => 'warning']);
            }

            foreach ($clothings as $clothing) {
                // Obtener la primera imagen
                $firstImage = ProductImage::where('clothing_id', $clothing->id)
                    ->orderBy('id')
                    ->first();
                $clothing->image = $firstImage ? $firstImage->image : null;
                $clothing->all_images = ProductImage::where('clothing_id', $clothing->id)
                    ->orderBy('id')
                    ->pluck('image')
                    ->toArray();

                // Obtener atributos
                $result = DB::table('stocks as s')->where('s.clothing_id', $clothing->id)
                    ->join('attributes as a', 's.attr_id', '=', 'a.id')
                    ->join('attribute_values as v', 's.value_attr', '=', 'v.id')
                    ->select(
                        'a.name as columna_atributo',
                        'a.id as attr_id',
                        DB::raw('GROUP_CONCAT(v.value ORDER BY s.order ASC SEPARATOR "/") as valores'),
                        DB::raw('GROUP_CONCAT(v.id ORDER BY s.order ASC SEPARATOR "/") as ids'),
                        DB::raw('GROUP_CONCAT(s.stock ORDER BY s.order ASC SEPARATOR "/") as stock'),
                    )
                    ->groupBy('a.name', 'a.id')
                    ->orderBy('a.name', 'asc')
                    ->get();
                // Limpiar atributos con stock 0
                $cleaned = $result->map(function ($item) {
                    $valores = explode('/', $item->valores);
                    $ids = explode('/', $item->ids);
                    $stock = explode('/', $item->stock);

                    // Filtrar solo los que tienen stock > 0
                    $filtered = collect($stock)
                        ->map(function ($s, $i) use ($valores, $ids) {
                            return [
                                'value' => $valores[$i],
                                'id' => $ids[$i],
                                'stock' => $s,
                            ];
                        })
                        ->filter(fn($x) => (int)$x['stock'] > 0)
                        ->values();

                    // Si después de filtrar no queda nada, lo descartamos
                    if ($filtered->isEmpty()) {
                        return null;
                    }

                    return (object)[
                        'columna_atributo' => $item->columna_atributo,
                        'attr_id' => $item->attr_id,
                        'valores' => $filtered->pluck('value')->implode('/'),
                        'ids' => $filtered->pluck('id')->implode('/'),
                        'stock' => $filtered->pluck('stock')->implode('/'),
                    ];
                })->filter()->values();

                $clothing->atributos = $cleaned->toArray();
            }
            $attributes = Attribute::with('values')->where('attributes.name', '!=', 'Stock')
            ->get();
            if ($tenantinfo->kind_of_features == 1) {
                return view('frontend.design_ecommerce.list-fav', compact('clothings', 'user','attributes'));
            }
            return view('frontend.list-fav', compact('clothings', 'user'));
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with(['status' => 'Fallo: ' . $th->getMessage(), 'icon' => 'warning']);
        }
    }
}
