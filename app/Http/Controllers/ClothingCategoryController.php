<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\MetaTags;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\SizeCloth;
use App\Models\Stock;
use App\Models\TenantInfo;
use App\Rules\FourImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;

class ClothingCategoryController extends Controller
{
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60; // Por ejemplo, 60 minutos
    }
    public function indexById($id)
    {
        $clothings = Cache::remember('clothings_' . $id, $this->expirationTime, function () use ($id) {
            return ClothingCategory::where('clothing.category_id', $id)
                ->where('clothing.status', 1)
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                ->leftJoin('attributes', 'stocks.attr_id', 'attributes.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')
                        ->whereRaw('product_images.id = (
                            SELECT MIN(id) FROM product_images 
                            WHERE product_images.clothing_id = clothing.id
                        )');
                })
                ->select(
                    'categories.name as category',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.code as code',
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE 0 END) as total_stock'),
                    DB::raw('GROUP_CONCAT(COALESCE(attributes.name, "")) AS available_attr'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('GROUP_CONCAT(COALESCE(stocks.attr_id, "")) AS attr_id_per_size'),
                    'product_images.image as image'
                )

                ->groupBy('clothing.id', 'clothing.casa', 'clothing.mayor_price', 'clothing.discount', 'categories.name', 'clothing.code', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
                ->get();
        });

        $category = Cache::remember('category_' . $id, $this->expirationTime, function () use ($id) {
            return Categories::find($id);
        });

        $category_name = $category->name;
        $category_id = $id;

        return view('admin.clothing.index', compact('clothings', 'category_name', 'category_id'));
    }
    public function add($id)
    {
        $category = Categories::find($id);
        $category_name = $category->name;
        $attributes = Attribute::get();
        return view('admin.clothing.add', compact('id', 'category_name', 'attributes'));
    }
    public function edit($id, $category_id)
    {
        $clothing = Cache::remember('clothing_' . $id, $this->expirationTime, function () use ($id) {
            return ClothingCategory::leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')
                        ->whereRaw('product_images.id = (
                            SELECT MIN(id) FROM product_images 
                            WHERE product_images.clothing_id = clothing.id
                        )');
                })
                ->where('clothing.id', $id)
                ->select(
                    'clothing.id as id',
                    'clothing.category_id as category_id',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.can_buy as can_buy',
                    'clothing.code as code',
                    'clothing.discount as discount',
                    'clothing.trending as trending',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE 0 END) as total_stock'),
                    'product_images.image as image' // Obtener la primera imagen del producto
                )
                ->groupBy(
                    'clothing.id',
                    'clothing.casa',
                    'clothing.name',
                    'clothing.code',
                    'clothing.can_buy',
                    'clothing.category_id',
                    'clothing.description',
                    'clothing.trending',
                    'clothing.price',
                    'clothing.mayor_price',
                    'product_images.image',
                    'clothing.discount'
                )
                ->first();
        });

        $stocks = Stock::where('clothing_id', $id)
            ->leftJoin('attributes', 'stocks.attr_id', 'attributes.id')
            ->leftJoin('attribute_values', 'stocks.value_attr', 'attribute_values.id')
            ->select(
                'stocks.id as id',
                'stocks.clothing_id as clothing_id',
                'stocks.stock as stock',
                'stocks.price as price',
                'stocks.attr_id as attr_id',
                'stocks.value_attr as value_attr',
                'attributes.name as name',
                'attributes.main as main',
                'attribute_values.id as value_id',
                'attribute_values.value as value',
            )
            ->get();
        $attributes = Attribute::get();
        $stock_active = Stock::where('clothing_id', $id)
            ->where('attr_id', '!=', "")
            ->leftJoin('attributes', 'stocks.attr_id', 'attributes.id')
            ->leftJoin('attribute_values', 'stocks.value_attr', 'attribute_values.id')
            ->select(
                'stocks.id as id',
                'stocks.clothing_id as clothing_id',
                'stocks.stock as stock',
                'stocks.price as price',
                'stocks.attr_id as attr_id',
                'stocks.value_attr as value_attr',
                'attributes.name as name',
                'attribute_values.id as value_id',
                'attribute_values.value as value',
            )
            ->first();
        return view('admin.clothing.edit', compact('clothing', 'stock_active', 'attributes', 'category_id', 'stocks'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        $tenantinfo = TenantInfo::first();
        try {
            $validator = Validator::make($request->all(), [
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validación básica de cada imagen
                'images' => [ // Regla personalizada para validar la cantidad máxima de imágenes
                    new FourImage,
                ],
            ]);

            if ($validator->fails() && isset($tenantinfo->tenant) && $tenantinfo->tenant != 'marylu') {
                return redirect('/new-item/' . $request->category_id)->with(['status' => 'No puede seleccionar más de 4 imágenes!', 'icon' => 'warning']);
            }

            $msg = $this->createClothing($request, $tenantinfo);
            DB::commit();
            return redirect()->back()->with(['status' => $msg, 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollback();
            return redirect()->back()->with(['status' => $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        $tenantinfo = TenantInfo::first();
        $size_continue = false;
        try {
            $validator = Validator::make($request->all(), [
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validación básica de cada imagen
                'images' => [ // Regla personalizada para validar la cantidad máxima de imágenes
                    new FourImage,
                ],
            ]);

            if ($validator->fails()) {
                return redirect('/edit-clothing/' . $id . '/' . $request->category_id)->with(['status' => 'No puede seleccionar más de 4 imágenes!', 'icon' => 'warning']);
            }
            $clothing = ClothingCategory::findOrfail($id);

            $clothing->category_id = $request->category_id;
            $clothing->name = $request->name;
            $clothing->code = $request->code;
            $clothing->description = $request->description;
            $clothing->price = $request->price;
            $prices = $request->input('precios');
            $quantities = $request->input('cantidades');
            $prices_attr = $request->input('precios_attr');
            $cantidades_attr = $request->input('cantidades_attr');

            if ($tenantinfo->tenant === "torres") {
                $clothing->mayor_price = $request->mayor_price;
            }
            if ($tenantinfo->tenant === "fragsperfumecr") {
                $clothing->casa = $request->casa;
            }

            if ($request->has('discount')) {
                $clothing->discount = $request->discount;
            }
            if ($tenantinfo->kind_business == 2 || $tenantinfo->kind_business == 3) {
                $clothing->can_buy = $request->filled('can_buy') ? 1 : 0;
            }

            $clothing->status = 1;

            if ($request->trending == 1) {
                $clothing->trending = 1;
            } else {
                $clothing->trending = 0;
            }

            $clothing->update();

            $imagesProduct =  ProductImage::where('clothing_id', $id)->get();

            if ($request->hasFile('images')) {
                foreach ($imagesProduct as $img) {
                    Storage::delete('public/' . $img->image);
                }

                ProductImage::where('clothing_id', $id)->delete();
                $images = $request->file('images');

                foreach ($images as $image) {
                    $imageObj = new ProductImage();
                    $imageObj->clothing_id = $id;
                    $imageObj->image = $image->store('uploads', 'public');
                    $imageObj->save();
                }
            }
            if (isset($tenantinfo->manage_size) && $tenantinfo->manage_size == 1) {              

                $validator = Validator::make($request->all(), [
                    'precios' => 'required|array|min:1', // Verifica que precios sea un array y que contenga al menos un elemento
                    'cantidades' => 'required|array|min:1', // Verifica que cantidades sea un array y que contenga al menos un elemento
                ]);

                // Si la validación falla, redirecciona de vuelta al formulario con los errores
                if (!$validator->fails()) {
                    foreach ($prices as $tallaId => $precio) {
                        $cantidad = $quantities[$tallaId];

                        $this->updateSizeStock($id, $cantidad, $precio, $tenantinfo->custom_size);
                    }
                }
            }

            $validator_attr = Validator::make($request->all(), [
                'precios_attr' => 'required|array|min:1', // Verifica que precios sea un array y que contenga al menos un elemento
                'cantidades_attr' => 'required|array|min:1'
            ]);
            // Si la validación falla, redirecciona de vuelta al formulario con los errores
            if (!$validator_attr->fails()) {
                foreach ($prices_attr as $itemId => $precio) {
                    $cantidad = $cantidades_attr[$itemId];
                    $attr_id = AttributeValue::where('id', $itemId)->first();

                    $this->updateAttr($id, $cantidad, $precio, $tenantinfo->custom_size, $attr_id->attribute_id, $itemId);
                }
                $size_continue = true;
            }

            DB::commit();
            return redirect('add-item/' . $request->category_id)->with(['status' => 'Producto Editado Con Exito!', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollback();
            return redirect()->back()->with(['status' => 'Ocurrió un error al editar el producto!' . $th->getMessage(), 'icon' => 'warning']);
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $clothing = ClothingCategory::findOrfail($id);

            $clothing_name = $clothing->name;
            if (
                Storage::delete('public/' . $clothing->image)

            ) {
                ClothingCategory::destroy($id);
            }
            Stock::where('clothing_id', $id)->delete();
            SizeCloth::where('clothing_id', $id)->delete();
            ClothingCategory::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => $clothing_name . ' se ha borrado el producto con éxito', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'Ocurrió un error al eliminar el producto!', 'icon' => 'error']);
        }
    }
    function createClothing($request, $tenantinfo)
    {
        $msg = "Producto Agregado Exitosamente!";
        $break = false;
        $code = 0;
        $size_continue = false;
        $latestCode = ClothingCategory::max('code');
        $prices = $request->input('precios');
        $quantities = $request->input('cantidades');
        $prices_attr = $request->input('precios_attr');
        $cantidades_attr = $request->input('cantidades_attr');
        $attr_id = $request->input('attr_id');

        if ($latestCode) {
            $code = $latestCode;
        }
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                $code = $code + 1;

                $clothing = new ClothingCategory();

                $clothing->category_id = $request->category_id;
                $clothing->name = $request->name;
                $clothing->code = $code;
                $clothing->description = $request->description;
                $clothing->price = $request->price;
                if ($tenantinfo->tenant === "torres") {
                    $clothing->mayor_price = $request->mayor_price;
                }
                if ($tenantinfo->tenant === "fragsperfumecr") {
                    $clothing->casa = $request->casa;
                }
                if ($request->has('discount')) {
                    $clothing->discount = $request->discount;
                }
                if ($tenantinfo->kind_business == 2 || $tenantinfo->kind_business == 3) {
                    $clothing->can_buy = $request->filled('can_buy') ? 1 : 0;
                }
                $clothing->status = 1;
                $clothing->trending = $request->filled('trending') ? 1 : 0;
                $clothing->save();
                $clothingId = $clothing->id;
                $masive = $request->filled('masive') ? 1 : 0;
                if ($masive == 1 && isset($tenantinfo->tenant) && $tenantinfo->tenant === 'marylu') {
                    $imageObj = new ProductImage();
                    $imageObj->clothing_id = $clothingId;
                    $imageObj->image = $image->store('uploads', 'public');
                    $imageObj->save();
                    $msg = "Productos agregados exitósamente";
                } else {
                    if ($request->hasFile('images')) {
                        $images = $request->file('images');

                        foreach ($images as $image) {
                            $imageObj = new ProductImage();
                            $imageObj->clothing_id = $clothingId;
                            $imageObj->image = $image->store('uploads', 'public');
                            $imageObj->save();
                        }
                    }

                    $break = true;
                }
                if (isset($tenantinfo->manage_size) && $tenantinfo->manage_size == 1) {

                    $validator = Validator::make($request->all(), [
                        'precios' => 'required|array|min:1', // Verifica que precios sea un array y que contenga al menos un elemento
                        'cantidades' => 'required|array|min:1'
                    ]);                    

                    // Si la validación falla, redirecciona de vuelta al formulario con los errores
                    if (!$validator->fails()) {
                        foreach ($prices as $tallaId => $precio) {
                            $cantidad = $quantities[$tallaId];

                            $this->processSize($clothingId, $cantidad, $precio);
                        }
                    }
                } else {
                    $this->processSize($clothingId, $request->stock);
                }
                $validator_attr = Validator::make($request->all(), [
                    'precios_attr' => 'required|array|min:1', // Verifica que precios sea un array y que contenga al menos un elemento
                    'cantidades_attr' => 'required|array|min:1'
                ]);

                // Si la validación falla, redirecciona de vuelta al formulario con los errores
                if (!$validator_attr->fails()) {
                    foreach ($prices_attr as $itemId => $precio) {
                        $cantidad = $cantidades_attr[$itemId];
                        $attr_id = AttributeValue::where('id', $itemId)->first();

                        $this->processAttr($clothingId, $cantidad, $precio, $attr_id->attribute_id, $itemId);
                    }
                    $size_continue = true;
                }

                if ($break) {
                    break;
                }
            }
        }
        return $msg;
    }
    public function processSize($clothingId, $stock, $price = null)
    {
        $stock_size = new Stock();
        $stock_size->clothing_id = $clothingId;
        $stock_size->stock = $stock;
        $stock_size->price = $price;
        $stock_size->save();
    }
    public function processAttr($clothingId, $stock, $price = null, $attr_id, $value)
    {
        $stock_size = new Stock();
        $stock_size->clothing_id = $clothingId;
        $stock_size->stock = $stock;
        $stock_size->attr_id = $attr_id;
        $stock_size->value_attr = $value;
        $stock_size->price = $price;
        $stock_size->save();
    }
    public function updateSizeStock($id, $stock, $price = null, $custom_size = null)
    {
        $stock_size = Stock::where('clothing_id', $id)
            ->where('attr_id', null)
            ->where('value_attr', null)
            ->first();
        if ($stock_size === null) {
            $stock_size =  new Stock();
            $stock_size->stock = $stock;
            $stock_size->price = $price;
            $stock_size->clothing_id = $id;
            $stock_size->save();
        } else {
            if (isset($custom_size) && $custom_size == 1) {
                Stock::where('clothing_id', $id)->update(['stock' => $stock, 'price' => $price]);
            } else {
                if ($stock_size->stock == 0) {
                    Stock::where('clothing_id', $id)->update(['stock' => $stock]);
                }
            }
        }
    }
    public function updateAttr($id, $stock, $price = null, $custom_size = null, $attr_id, $value)
    {
        $stock_size = Stock::where('clothing_id', $id)
            ->where('attr_id', $attr_id)
            ->where('value_attr', $value)
            ->first();
        if ($stock_size === null) {
            $stock_size =  new Stock();
            $stock_size->stock = $stock;
            $stock_size->attr_id = $attr_id;
            $stock_size->value_attr = $value;
            $stock_size->price = $price;
            $stock_size->clothing_id = $id;
            $stock_size->save();
        } else {
            Stock::where('clothing_id', $id)
                ->where('attr_id', $attr_id)
                ->where('value_attr', $value)
                ->update([
                    'stock' => $stock,
                    'price' => $price,
                    'attr_id' => $attr_id,
                    'value_attr' => $value
                ]);
        }
    }
}
