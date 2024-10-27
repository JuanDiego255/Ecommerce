<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\ClothingDetails;
use App\Models\PivotClothingCategory;
use App\Models\ProductImage;
use App\Models\Stock;
use App\Models\TenantInfo;
use App\Rules\FourImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
            return DB::table('clothing')
                ->join('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->join('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                ->leftJoin('stocks', 'clothing.id', '=', 'stocks.clothing_id')
                ->leftJoin('attributes', 'stocks.attr_id', '=', 'attributes.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                            SELECT MIN(id) FROM product_images
                            WHERE product_images.clothing_id = clothing.id
                        )');
                })
                ->where('pivot_clothing_categories.category_id', $id)
                ->select('categories.name as category', 'clothing.id as id', 'clothing.trending as trending', 'clothing.name as name', 'clothing.casa as casa', 'clothing.code as code', 'clothing.status as status', 'clothing.manage_stock as manage_stock', 'clothing.discount as discount', 'clothing.description as description', 'clothing.price as price', 'clothing.mayor_price as mayor_price', DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE 0 END) as total_stock'), DB::raw('GROUP_CONCAT(COALESCE(attributes.name, "")) AS available_attr'), DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), DB::raw('GROUP_CONCAT(COALESCE(stocks.attr_id, "")) AS attr_id_per_size'), 'product_images.image as image')
                ->groupBy('clothing.id', 'clothing.casa', 'clothing.mayor_price', 'clothing.discount', 'categories.name', 'clothing.code', 'clothing.status', 'clothing.manage_stock', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
                ->orderBy('name', 'asc')
                ->get();
        });

        $category = Cache::remember('category_' . $id, $this->expirationTime, function () use ($id) {
            return Categories::find($id);
        });

        $category_name = $category->name;
        $category_id = $id;
        $department_id = $category->department_id;

        return view('admin.clothing.index', compact('clothings', 'category_name', 'category_id', 'department_id'));
    }
    public function add($id)
    {
        $category = Categories::find($id);
        $category_name = $category->name;
        $category_id = $category->id;
        $attributes = Attribute::where('name', '!=', 'Stock')->get();
        return view('admin.clothing.add', compact('id', 'category_name', 'attributes', 'category_id'));
    }
    public function edit($id, $category_id)
    {
        $categories = Categories::orderBy('name', 'asc')->join('departments', 'categories.department_id', 'departments.id')->select('categories.id as id', 'categories.name as name', 'departments.department as department')->get();
        $selectedCategories = [];
        $selectedCategoriesSql = PivotClothingCategory::where('clothing_id', $id)->get('category_id');
        foreach ($selectedCategoriesSql as $selected_category) {
            array_push($selectedCategories, $selected_category->category_id);
        }
        $clothing = Cache::remember('clothing_' . $id, $this->expirationTime, function () use ($id) {
            return ClothingCategory::leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                            SELECT MIN(id) FROM product_images
                            WHERE product_images.clothing_id = clothing.id
                        )');
                })
                ->where('clothing.id', $id)
                ->select(
                    'clothing.id as id',
                    'pivot_clothing_categories.category_id as category_id',
                    'categories.name as category_name',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.manage_stock as manage_stock',
                    'clothing.can_buy as can_buy',
                    'clothing.horizontal_image as horizontal_image',
                    'clothing.main_image as main_image',
                    'clothing.code as code',
                    'clothing.discount as discount',
                    'clothing.trending as trending',
                    'clothing.description as description',
                    'clothing.meta_keywords as meta_keywords',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE 0 END) as total_stock'),
                    'product_images.image as image', // Obtener la primera imagen del producto
                )
                ->groupBy('clothing.id','clothing.main_image', 'clothing.horizontal_image', 'clothing.casa', 'clothing.name', 'clothing.manage_stock', 'clothing.code', 'clothing.can_buy', 'pivot_clothing_categories.category_id', 'categories.name', 'clothing.description', 'clothing.trending', 'clothing.price', 'clothing.mayor_price', 'clothing.meta_keywords', 'product_images.image', 'clothing.discount')
                ->first();
        });


        $details = ClothingDetails::where('clothing_id', $id)->first();
        $stocks = Stock::where('clothing_id', $id)->leftJoin('attributes', 'stocks.attr_id', 'attributes.id')->leftJoin('attribute_values', 'stocks.value_attr', 'attribute_values.id')->select('stocks.id as id', 'stocks.clothing_id as clothing_id', 'stocks.stock as stock', 'stocks.price as price', 'stocks.attr_id as attr_id', 'stocks.value_attr as value_attr', 'attributes.name as name', 'attributes.main as main', 'attribute_values.id as value_id', 'attribute_values.value as value')->get();
        $attributes = Attribute::where('name', '!=', 'Stock')->get();
        $stock_active = Stock::where('clothing_id', $id)->where('attr_id', '!=', '')->leftJoin('attributes', 'stocks.attr_id', 'attributes.id')->leftJoin('attribute_values', 'stocks.value_attr', 'attribute_values.id')->select('stocks.id as id', 'stocks.clothing_id as clothing_id', 'stocks.stock as stock', 'stocks.price as price', 'stocks.attr_id as attr_id', 'stocks.value_attr as value_attr', 'attributes.name as name', 'attribute_values.id as value_id', 'attribute_values.value as value')->first();
        return view('admin.clothing.edit', compact('clothing', 'selectedCategories', 'details', 'stock_active', 'category_id', 'attributes', 'categories', 'stocks'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        $tenantinfo = TenantInfo::first();
        try {
            $validator = Validator::make($request->all(), [
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación básica de cada imagen
                'images' => [
                    // Regla personalizada para validar la cantidad máxima de imágenes
                    new FourImage(),
                ],
            ]);

            if ($validator->fails() && isset($tenantinfo->tenant) && $tenantinfo->tenant != 'marylu') {
                return redirect('/new-item/' . $request->category_id)->with(['status' => '(El formato de la imagen debe ser: jpg,png,jpeg,gif,svg. Max(2048), permitidas solo 4 imagenes)', 'icon' => 'warning']);
            }

            $msg = $this->createClothing($request, $tenantinfo);
            DB::commit();
            return redirect()
                ->back()
                ->with(['status' => $msg, 'icon' => 'success']);
        } catch (Exception $th) {
            dd($th->getMessage());
            DB::rollback();
            return redirect()
                ->back()
                ->with(['status' => 'Ocurrió un error al agregar el producto, verifique que no hayan campos en blanco', 'icon' => 'error'])
                ->withInput();
        }
    }
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        $tenantinfo = TenantInfo::first();
        try {
            $validator = Validator::make($request->all(), [
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validación básica de cada imagen
                'images' => [
                    // Regla personalizada para validar la cantidad máxima de imágenes
                    new FourImage(),
                ],
            ]);

            if ($validator->fails()) {
                return redirect('/edit-clothing/' . $id . '/' . $request->category_id)->with(['status' => '(El formato de la imagen debe ser: jpg,png,jpeg,gif,svg. Max(2048), permitidas solo 4 imagenes)', 'icon' => 'warning']);
            }
            $clothing = ClothingCategory::findOrfail($id);
            $clothing->name = $request->name;
            $clothing->code = $request->code;
            $clothing->description = $request->description;
            $clothing->price = $request->price;
            $prices_attr = $request->input('precios_attr');
            $cantidades_attr = $request->input('cantidades_attr');

            if ($tenantinfo->tenant === 'torres') {
                $clothing->mayor_price = $request->mayor_price;
            }
            if ($tenantinfo->tenant === 'fragsperfumecr') {
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
            $clothing->meta_keywords = $request->meta_keywords;
            $clothing->manage_stock = $request->manage_stock ? 1 : 0;
            if ($request->hasFile('horizontal_image')) {
                $image = $request->file('horizontal_image');
                $clothing->horizontal_image = $image->store('uploads', 'public');
            }
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $clothing->main_image = $image->store('uploads', 'public');
            }

            $clothing->update();

            if ($tenantinfo->kind_business == 1) {
                $car_details = ClothingDetails::where('clothing_id', $id)->first();
                if ($car_details == null) {
                    $car_detail = new ClothingDetails();
                    $car_detail->clothing_id = $id;
                    $car_detail->distancia_suelo = $request->distancia_suelo;
                    $car_detail->color = $request->color;
                    $car_detail->modelo = $request->modelo;
                    $car_detail->kilometraje = $request->kilometraje;
                    $car_detail->peso = $request->peso;
                    $car_detail->capacidad_tanque = $request->capacidad_tanque;
                    $car_detail->combustible = $request->combustible;
                    $car_detail->motor = $request->motor;
                    $car_detail->potencia = $request->potencia;
                    $car_detail->pasajeros = $request->pasajeros;
                    $car_detail->llantas = $request->llantas;
                    $car_detail->traccion = $request->traccion;
                    $car_detail->transmision = $request->transmision;
                    $car_detail->largo = $request->largo;
                    $car_detail->ancho = $request->ancho;
                    $car_detail->save();
                } else {
                    $car_detail = ClothingDetails::findOrfail($car_details->id);
                    $car_detail->distancia_suelo = $request->distancia_suelo;
                    $car_detail->peso = $request->peso;
                    $car_detail->color = $request->color;
                    $car_detail->modelo = $request->modelo;
                    $car_detail->kilometraje = $request->kilometraje;
                    $car_detail->capacidad_tanque = $request->capacidad_tanque;
                    $car_detail->combustible = $request->combustible;
                    $car_detail->motor = $request->motor;
                    $car_detail->potencia = $request->potencia;
                    $car_detail->pasajeros = $request->pasajeros;
                    $car_detail->llantas = $request->llantas;
                    $car_detail->traccion = $request->traccion;
                    $car_detail->transmision = $request->transmision;
                    $car_detail->largo = $request->largo;
                    $car_detail->ancho = $request->ancho;
                    $car_detail->update();
                }
            }
            //Procesar categorias
            $selectedCategories = $request->input('category_id');
            $categoriesItemIds = array_keys($selectedCategories);
            $currentCategoriesRecords = PivotClothingCategory::where('clothing_id', $id)->get();
            foreach ($currentCategoriesRecords as $record) {
                if (!in_array($record->category_id, $categoriesItemIds)) {
                    $record->delete();
                }
            }

            foreach ($selectedCategories as $selected_category) {
                $exists = PivotClothingCategory::where('category_id', $selected_category)->where('clothing_id', $id)->exists();

                if (!$exists) {
                    $pivot_cat = new PivotClothingCategory();
                    $pivot_cat->category_id = $selected_category;
                    $pivot_cat->clothing_id = $id;
                    $pivot_cat->save();
                }
            }

            //Porocesar categorias fin

            $imagesProduct = ProductImage::where('clothing_id', $id)->get();

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
                if (!empty($prices_attr)) {
                    $requestItemIds = array_keys($prices_attr);
                    $updateAttr = true;
                } else {
                    $updateAttr = false;
                    $requestItemIds = [];
                }
                $currentClothingRecords = Stock::where('clothing_id', $id)->get();
                foreach ($currentClothingRecords as $record) {
                    if (!in_array($record->attribute_value_id, $requestItemIds)) {
                        $record->delete();
                    }
                }
                $validator_attr = Validator::make($request->all(), [
                    'precios_attr' => 'required|array|min:1', // Verifica que precios sea un array y que contenga al menos un elemento
                    'cantidades_attr' => 'required|array|min:1',
                ]);
                // Si la validación falla, redirecciona de vuelta al formulario con los errores
                if (!$validator_attr->fails()) {
                    $count = 1;
                    foreach ($prices_attr as $itemId => $precio) {                        
                        $cantidad = $cantidades_attr[$itemId];
                        $correct_price = 0;
                        $attr_id = AttributeValue::where('attribute_values.id', $itemId)->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attribute_id', 'attribute_values.id as value_id', 'attributes.main as main')->first();
                        if ($attr_id->main != 0) {
                            $correct_price = $precio > 0 ? $precio : $request->price;
                        }
                        $correct_qty = $cantidad > 0 ? $cantidad : $request->stock;
                        $this->updateAttr($id, $correct_qty, $correct_price, $attr_id->attribute_id, $itemId, $request->manage_stock,$count);
                        $count++;
                    }
                } else {
                    if ($updateAttr) {
                        $value = AttributeValue::where('attributes.name', 'Stock')->where('attribute_values.value', 'Automático')->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attr_id', 'attribute_values.id as value_id')->first();
                        $this->updateAttr($id, $request->stock, $request->price, $value->attr_id, $value->value_id, $request->manage_stock,1);
                    }
                }
            } else {
                $value = AttributeValue::where('attributes.name', 'Stock')->where('attribute_values.value', 'Automático')->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attr_id', 'attribute_values.id as value_id')->first();
                $this->updateAttr($id, $request->stock, $request->price, $value->attr_id, $value->value_id, $request->manage_stock,1);
            }

            DB::commit();
            return redirect('add-item/' . $request->category_id_main)->with(['status' => 'Producto Editado Con Exito!', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollback();
            return redirect()
                ->back()
                ->with(['status' => 'Ocurrió un error al editar el producto!' . $th->getMessage(), 'icon' => 'warning']);
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $clothing = ClothingCategory::findOrfail($id);

            $clothing_name = $clothing->name;
            if (Storage::delete('public/' . $clothing->image)) {
                ClothingCategory::destroy($id);
            }
            Stock::where('clothing_id', $id)->delete();
            ClothingCategory::destroy($id);
            DB::commit();
            return response()->json(['status' => 'Se ha eliminado el artículo del carrito', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json(['status' => 'No se pudo eliminar el producto', 'icon' => 'error']);
        }
    }
    function createClothing($request, $tenantinfo)
    {
        $msg = 'Producto Agregado Exitosamente!';
        $break = false;
        $code = 0;
        $prices_attr = $request->input('precios_attr');
        $cantidades_attr = $request->input('cantidades_attr');
        $attr_id = $request->input('attr_id');
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                $code = $this->generateSku($request->code);
                $clothing = new ClothingCategory();
                $clothing->name = $request->name;
                $clothing->code = $code;
                $clothing->description = $request->description;
                $clothing->price = $request->price;
                if ($request->hasFile('horizontal_image')) {
                    $image = $request->file('horizontal_image');
                    $clothing->horizontal_image = $image->store('uploads', 'public');
                }
                if ($request->hasFile('main_image')) {
                    $image = $request->file('main_image');
                    $clothing->main_image = $image->store('uploads', 'public');
                }
                if ($tenantinfo->tenant === 'torres') {
                    $clothing->mayor_price = $request->mayor_price;
                }
                if ($tenantinfo->tenant === 'fragsperfumecr') {
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
                $clothing->meta_keywords = $request->meta_keywords;
                $clothing->manage_stock = $request->manage_stock ? 1 : 0;
                $clothing->save();
                $clothingId = $clothing->id;

                //Ligar categoria al producto
                $clothing_category = new PivotClothingCategory();
                $clothing_category->category_id = $request->category_id;
                $clothing_category->clothing_id = $clothingId;
                $clothing_category->save();

                //Especificaciones de vehiculos, solo para tipo de negocio 1
                if ($tenantinfo->kind_business == 1) {
                    $car_detail = new ClothingDetails();
                    $car_detail->clothing_id = $clothingId;
                    $car_detail->distancia_suelo = $request->distancia_suelo;
                    $car_detail->color = $request->color;
                    $car_detail->modelo = $request->modelo;
                    $car_detail->kilometraje = $request->kilometraje;
                    $car_detail->peso = $request->peso;
                    $car_detail->capacidad_tanque = $request->capacidad_tanque;
                    $car_detail->combustible = $request->combustible;
                    $car_detail->motor = $request->motor;
                    $car_detail->potencia = $request->potencia;
                    $car_detail->pasajeros = $request->pasajeros;
                    $car_detail->llantas = $request->llantas;
                    $car_detail->traccion = $request->traccion;
                    $car_detail->transmision = $request->transmision;
                    $car_detail->largo = $request->largo;
                    $car_detail->ancho = $request->ancho;
                    $car_detail->save();
                }

                $masive = $request->filled('masive') ? 1 : 0;
                if ($masive == 1 && isset($tenantinfo->tenant) && $tenantinfo->tenant === 'marylu') {
                    $imageObj = new ProductImage();
                    $imageObj->clothing_id = $clothingId;
                    $imageObj->image = $image->store('uploads', 'public');
                    $imageObj->save();
                    $msg = 'Productos agregados exitósamente';
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
                    $validator_attr = Validator::make($request->all(), [
                        'precios_attr' => 'required|array|min:1', // Verifica que precios sea un array y que contenga al menos un elemento
                        'cantidades_attr' => 'required|array|min:1',
                    ]);
                    // Si la validación falla, redirecciona de vuelta al formulario con los errores
                    if (!$validator_attr->fails()) {
                        $count = 1;
                        foreach ($prices_attr as $itemId => $precio) {                           
                            $cantidad = $cantidades_attr[$itemId];
                            $correct_price = 0;
                            $attr_id = AttributeValue::where('attribute_values.id', $itemId)->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attribute_id', 'attribute_values.id as value_id', 'attributes.main as main')->first();
                            if ($attr_id->main != 0) {
                                $correct_price = $precio > 0 ? $precio : $request->price;
                            }
                            $correct_qty = $cantidad > 0 ? $cantidad : $request->stock;
                            $this->processAttr($clothingId, $correct_qty, $correct_price, $attr_id->attribute_id, $itemId, $request->manage_stock,$count);
                            $count++;
                        }
                    } else {
                        $value = AttributeValue::where('attributes.name', 'Stock')->where('attribute_values.value', 'Automático')->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attr_id', 'attribute_values.id as value_id')->first();
                        $this->processAttr($clothingId, $request->stock, $request->price, $value->attr_id, $value->value_id, $request->manage_stock,1);
                    }
                } else {
                    $value = AttributeValue::where('attributes.name', 'Stock')->where('attribute_values.value', 'Automático')->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attr_id', 'attribute_values.id as value_id')->first();
                    $this->processAttr($clothingId, $request->stock, $request->price, $value->attr_id, $value->value_id, $request->manage_stock,1);
                }

                if ($break) {
                    break;
                }
            }
        } else {
            $value = AttributeValue::where('attributes.name', 'Stock')->where('attribute_values.value', 'Automático')->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attr_id', 'attribute_values.id as value_id')->first();
            $clothing = new ClothingCategory();
            $clothing->name = $request->name;
            $clothing->code = $request->code;
            $clothing->description = $request->description;
            $clothing->price = $request->price;
            $clothing->status = 1;
            $clothing->trending = $request->filled('trending') ? 1 : 0;
            $clothing->save();
            $clothingId = $clothing->id;
            //Ligar categoria al producto
            $clothing_category = new PivotClothingCategory();
            $clothing_category->category_id = $request->category_id;
            $clothing_category->clothing_id = $clothingId;
            $clothing_category->save();
            $masive = $request->filled('masive') ? 1 : 0;
            $this->processAttr($clothingId, $request->stock, $request->price, $value->attr_id, $value->value_id, $request->manage_stock,1);
        }
        return $msg;
    }
    public function processAttr($clothingId, $stock, $price = null, $attr_id, $value, $manage_stock,$order)
    {
        $stock_size = new Stock();
        $stock_size->clothing_id = $clothingId;
        $stock_size->stock = $manage_stock == 1 ? $stock : -1;
        $stock_size->attr_id = $attr_id;
        $stock_size->value_attr = $value;
        $stock_size->price = $price;
        $stock_size->order = $order;
        $stock_size->save();
    }
    public function updateAttr($id, $stock, $price = null, $attr_id, $value, $manage_stock,$order)
    {
        $stock_size = Stock::where('clothing_id', $id)->where('attr_id', $attr_id)->where('value_attr', $value)->first();
        if ($stock_size === null) {
            $stock_size = new Stock();
            $stock_size->stock = $manage_stock == 1 ? $stock : -1;
            $stock_size->attr_id = $attr_id;
            $stock_size->value_attr = $value;
            $stock_size->price = $price;
            $stock_size->clothing_id = $id;
            $stock_size->order = $order;
            $stock_size->save();
        } else {
            Stock::where('clothing_id', $id)
                ->where('attr_id', $attr_id)
                ->where('value_attr', $value)
                ->update([
                    'stock' => $stock,
                    'price' => $price,
                    'attr_id' => $attr_id,
                    'value_attr' => $value,
                    'order' => $order
                ]);
        }
    }
    public function importProducts(Request $request, $id)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);
            Excel::import(new ProductsImport($id), $request->file('file'));
            return redirect()
                ->back()
                ->with(['status' => 'Se importaron los productos correctamente', 'icon' => 'success']);
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with(['status' => 'No se importaron los productos!' . $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function isStatus($id, Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->status == '1') {
                ClothingCategory::where('id', $id)->update(['status' => 1]);
            } else {
                ClothingCategory::where('id', $id)->update(['status' => 0]);
            }
            DB::commit();
            return redirect()
                ->back()
                ->with(['status' => 'Se cambio el estado del producto', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    public function getTotalCategories($id)
    {
        $total = PivotClothingCategory::where('clothing_id', $id)->count();
        return response()->json($total);
    }
    function generateSku($code)
    {

        if ($code == null) {
            $prefix = 'P';
            $randomNumbers = str_pad(mt_rand(1, 9999999999999), 13, '0', STR_PAD_LEFT);
            $sku = $prefix . $randomNumbers;
        } else {
            $sku = $code;
        }

        if (ClothingCategory::where('code', $sku)->exists()) {
            $this->generateSku(null);
        }

        return $sku;
    }
    public function getCartDetail($code)
    {
        $clothes = ClothingCategory::where('clothing.code', $code)
            ->where('clothing.status', 1)
            ->join('clothing_details', 'clothing.id', 'clothing_details.clothing_id')
            ->join('product_images', 'clothing.id', '=', 'product_images.clothing_id')
            ->select(
                'clothing.id as id',
                'clothing.name as name',
                'clothing.price as price',
                'clothing_details.distancia_suelo as distance',
                'clothing_details.peso as weight',
                'clothing_details.capacidad_tanque as tank_capacity',
                'clothing_details.color as color',
                'clothing_details.modelo as model',
                'clothing_details.kilometraje as mileage',
                'clothing_details.combustible as fuel_type',
                'clothing_details.motor as engine',
                'clothing_details.potencia as power',
                'clothing_details.pasajeros as passengers',
                'clothing_details.llantas as tires',
                'clothing_details.traccion as traction',
                'clothing_details.transmision as transmission',
                'clothing_details.largo as length',
                'clothing_details.ancho as width',
                'product_images.image as image'
            )
            ->get();
        return response()->json(['status' => 'success', 'results' => $clothes]);
    }
}
