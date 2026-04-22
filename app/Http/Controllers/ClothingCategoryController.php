<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Categories;
use App\Models\Department;
use App\Models\ClothingCategory;
use App\Models\ClothingDetails;
use App\Models\PivotClothingCategory;
use App\Models\ProductImage;
use App\Models\Stock;
use App\Models\TenantInfo;
use App\Models\VariantCombination;
use App\Models\VariantCombinationValue;
use App\Rules\FourImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

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
        $statusFilter = request()->get('status', 2);
        $stockFilter  = request()->get('stock', ''); // 'low' | 'out' | ''

        $clothings = Cache::remember('clothings_' . $id . '_' . $statusFilter . '_' . $stockFilter, $this->expirationTime, function () use ($id, $statusFilter, $stockFilter) {
            $query = DB::table('clothing')
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
                ->where('pivot_clothing_categories.category_id', $id);

            if ($statusFilter != 2) {
                $query->where('clothing.status', $statusFilter);
            }

            $query->select(
                    'categories.name as category',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.code as code',
                    'clothing.status as status',
                    'clothing.manage_stock as manage_stock',
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('COALESCE((SELECT SUM(vc.stock) FROM variant_combinations vc WHERE vc.clothing_id = clothing.id AND vc.stock >= 0), SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END)) as total_stock'),
                    DB::raw('GROUP_CONCAT(COALESCE(attributes.name, "")) AS available_attr'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('GROUP_CONCAT(COALESCE(stocks.attr_id, "")) AS attr_id_per_size'),
                    'product_images.image as image'
                )
                ->groupBy('clothing.id', 'clothing.casa', 'clothing.mayor_price', 'clothing.discount', 'categories.name', 'clothing.code', 'clothing.status', 'clothing.manage_stock', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
                ->orderBy('name', 'asc');

            if ($stockFilter === 'low') {
                $query->havingRaw('clothing.manage_stock = 1 AND total_stock > 0 AND total_stock <= 5');
            } elseif ($stockFilter === 'out') {
                $query->havingRaw('clothing.manage_stock = 1 AND total_stock <= 0');
            }

            return $query->get();
        });

        $category = Cache::remember('category_' . $id, $this->expirationTime, function () use ($id) {
            return Categories::find($id);
        });

        $category_name = $category->name;
        $category_id = $id;
        $department_id = $category->department_id;

        $categories = Cache::remember('categories_dept_' . $department_id, $this->expirationTime, function () use ($department_id) {
            return Categories::where('department_id', $department_id)->orderBy('name', 'asc')->get();
        });
        $department_name = Cache::remember('department_name_' . $department_id, $this->expirationTime, function () use ($department_id) {
            $dept = Department::find($department_id);
            return $dept ? $dept->department : '';
        });

        if (request()->ajax()) {
            return DataTables::of($clothings)
                ->addColumn('bulk_check', function ($item) {
                    return '<input type="checkbox" class="bulk-cb" value="' . $item->id . '">';
                })
                ->addColumn('status', function ($item) {
                    return '<div class="form-check d-flex justify-content-center">
                                <input id="checkLicense' . $item->id . '" class="form-check-input changeStatus"
                                    type="checkbox" value="' . $item->id . '"
                                    ' . ($item->status == 1 ? 'checked' : '') . '>
                            </div>';
                })
                ->addColumn('acciones', function ($item) use ($id) {
                    $attr = explode(',', $item->available_attr ?? '');
                    $attrPer = explode(',', $item->attr_id_per_size ?? '');
                    $hasAttr = false;
                    for ($i = 0; $i < count($attr); $i++) {
                        if (!empty($attrPer[$i]) && $attr[$i] !== 'Stock') { $hasAttr = true; break; }
                    }
                    return '<div class="act-group">
                                <button class="act-btn ab-del btnDeleteItem"
                                    data-item-id="' . $item->id . '" title="Eliminar">
                                    <span class="material-icons">delete</span>
                                </button>
                                <a class="act-btn ab-neutral"
                                    href="' . url('/edit-clothing') . '/' . $item->id . '/' . $id . '" title="Editar">
                                    <span class="material-icons">edit</span>
                                </a>
                                <button class="act-btn ab-neutral btnQuickEdit"
                                    data-item-id="' . $item->id . '"
                                    data-item-name="' . e($item->name) . '"
                                    data-has-attr="' . ($hasAttr ? '1' : '0') . '"
                                    title="Edición rápida">
                                    <span class="material-icons">bolt</span>
                                </button>
                            </div>';
                })
                ->addColumn('name', function ($item) {
                    $img = isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG');
                    return '<div class="d-flex px-2 py-1">
                                <div>
                                    <a target="blank" data-fancybox="gallery" href="' . $img . '">
                                        <img src="' . $img . '" class="avatar avatar-md me-3" loading="lazy">
                                    </a>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h4 class="mb-0 text-lg">' . e($item->name) . '</h4>
                                    <p class="text-xs text-secondary mb-0 d-flex align-items-center gap-1">
                                        Código: <span>' . e($item->code) . '</span>
                                        <button class="copy-sku btn-icon" data-sku="' . e($item->code) . '" title="Copiar código">
                                            <span class="material-icons" style="font-size:.85rem;vertical-align:middle;cursor:pointer;color:var(--gray3)">content_copy</span>
                                        </button>
                                    </p>
                                </div>
                            </div>';
                })
                ->addColumn('price', function ($item) {
                    return '<td class="align-middle text-center text-sm">
                                <p class="text-success mb-0">₡' . number_format($item->price) . '</p>
                            </td>';
                })
                ->addColumn('atributos', function ($item) {
                    // Convertir las cadenas en arreglos
                    $stockPerSize = explode(',', $item->stock_per_size);
                    $attrPerItem = explode(',', $item->attr_id_per_size);
                    $attr = explode(',', $item->available_attr);

                    $exist_attr = false;
                    for ($i = 0; $i < count($attr); $i++) {
                        if (!empty($attrPerItem[$i]) && $attr[$i] !== 'Stock') {
                            $exist_attr = true;
                            break;
                        }
                    }

                    return '<td class="align-middle text-center text-sm">
                                <p class="mb-0">' . ($exist_attr ? __('Con atributos') : __('Sin atributos')) . '</p>
                            </td>';
                })
                ->addColumn('stock', function ($item) {
                    if ($item->manage_stock == 0) {
                        return '<span class="stock-badge" style="background:var(--gray0);color:var(--gray3)">No maneja</span>';
                    }
                    $s = (int) $item->total_stock;
                    $cls = $s <= 0 ? 'stock-out' : ($s <= 5 ? 'stock-low' : 'stock-ok');
                    return '<span class="stock-badge ' . $cls . '">' . $s . '</span>';
                })
                ->rawColumns(['bulk_check', 'status', 'acciones', 'name', 'price', 'atributos', 'stock'])
                ->toJson();
        }

        return view('admin.clothing.index', compact('category_name', 'category_id', 'department_id', 'department_name', 'categories'));
    }
    public function reportStock()
    {
        $clothings = Cache::remember('clothings_report', $this->expirationTime, function () {
            return DB::table('clothing')
                ->where('clothing.status', 1)
                ->leftJoin('stocks', 'clothing.id', '=', 'stocks.clothing_id')
                ->leftJoin('attributes', 'stocks.attr_id', '=', 'attributes.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                            SELECT MIN(id) FROM product_images
                            WHERE product_images.clothing_id = clothing.id
                        )');
                })
                ->select(
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.code as code',
                    'clothing.status as status',
                    'clothing.manage_stock as manage_stock',
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('COALESCE((SELECT SUM(vc.stock) FROM variant_combinations vc WHERE vc.clothing_id = clothing.id AND vc.stock >= 0), SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END)) as total_stock'),
                    DB::raw('GROUP_CONCAT(COALESCE(attributes.name, "")) AS available_attr'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('GROUP_CONCAT(COALESCE(stocks.attr_id, "")) AS attr_id_per_size'),
                    'product_images.image as image'
                )
                ->groupBy(
                    'clothing.id',
                    'clothing.casa',
                    'clothing.mayor_price',
                    'clothing.discount',
                    'clothing.code',
                    'clothing.status',
                    'clothing.manage_stock',
                    'clothing.name',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'product_images.image'
                )
                ->orderBy('name', 'asc')
                ->get();
        });

        return view('admin.reports.stock', compact('clothings'));
    }
    public function add($id)
    {
        $category = Categories::find($id);
        $category_name = $category->name;
        $category_id = $category->id;
        $attributes = Attribute::where('name', '!=', 'Stock')->with('values')->get();
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
                    'clothing.is_contra_pedido as is_contra_pedido',
                    'clothing.discount as discount',
                    'clothing.trending as trending',
                    'clothing.description as description',
                    'clothing.meta_keywords as meta_keywords',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('COALESCE((SELECT SUM(vc.stock) FROM variant_combinations vc WHERE vc.clothing_id = clothing.id AND vc.stock >= 0), SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END)) as total_stock'),
                    'product_images.image as image', // Obtener la primera imagen del producto
                )
                ->groupBy('clothing.id', 'clothing.is_contra_pedido', 'clothing.main_image', 'clothing.horizontal_image', 'clothing.casa', 'clothing.name', 'clothing.manage_stock', 'clothing.code', 'clothing.can_buy', 'pivot_clothing_categories.category_id', 'categories.name', 'clothing.description', 'clothing.trending', 'clothing.price', 'clothing.mayor_price', 'clothing.meta_keywords', 'product_images.image', 'clothing.discount')
                ->first();
        });


        $details = ClothingDetails::where('clothing_id', $id)->first();
        $stocks = Stock::where('clothing_id', $id)->leftJoin('attributes', 'stocks.attr_id', 'attributes.id')->leftJoin('attribute_values', 'stocks.value_attr', 'attribute_values.id')->select('stocks.id as id', 'stocks.clothing_id as clothing_id', 'stocks.stock as stock', 'stocks.price as price', 'stocks.attr_id as attr_id', 'stocks.value_attr as value_attr', 'attributes.name as name', 'attributes.main as main', 'attribute_values.id as value_id', 'attribute_values.value as value')->get();
        $combinations = VariantCombination::where('clothing_id', $id)->with('values')->get();
        $attributes = Attribute::where('name', '!=', 'Stock')->with('values')->get();
        $stock_active = Stock::where('clothing_id', $id)->where('attr_id', '!=', '')->leftJoin('attributes', 'stocks.attr_id', 'attributes.id')->leftJoin('attribute_values', 'stocks.value_attr', 'attribute_values.id')->select('stocks.id as id', 'stocks.clothing_id as clothing_id', 'stocks.stock as stock', 'stocks.price as price', 'stocks.attr_id as attr_id', 'stocks.value_attr as value_attr', 'attributes.name as name', 'attribute_values.id as value_id', 'attribute_values.value as value')->first();
        return view('admin.clothing.edit', compact('clothing', 'selectedCategories', 'details', 'stock_active', 'category_id', 'attributes', 'categories', 'stocks', 'combinations'));
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
            $contenido = $request->input('description');
            $contenidoEscapado = str_replace('"', '&quot;', $contenido);
            $clothing = ClothingCategory::findOrfail($id);
            $clothing->name = $request->name;
            $clothing->code = $request->code;
            $clothing->description = $contenidoEscapado;
            $clothing->stock = $request->stock;
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
            if ($tenantinfo->kind_business == 2 || $tenantinfo->kind_business == 3 || $tenantinfo->tenant === 'muebleriasarchi') {
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
            $clothing->is_contra_pedido = $request->is_contra_pedido ? 1 : 0;
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
                $combos = $request->input('combos', []);
                if (!empty($combos)) {
                    // Delete combinations removed by the user
                    $submittedIds = collect($combos)
                        ->pluck('combination_id')
                        ->filter(fn($v) => $v !== '' && $v !== null)
                        ->map('intval')
                        ->toArray();
                    VariantCombination::where('clothing_id', $id)
                        ->when(!empty($submittedIds), fn($q) => $q->whereNotIn('id', $submittedIds))
                        ->delete();
                    $this->processCombos($id, $combos, $request->manage_stock);
                } else {
                    // Fall back to legacy precios_attr[] format
                    if (!empty($prices_attr)) {
                        $count = 1;
                        foreach ($prices_attr as $itemId => $precio) {
                            $cantidad = $cantidades_attr[$itemId];
                            $attr_id = AttributeValue::where('attribute_values.id', $itemId)->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attribute_id', 'attribute_values.id as value_id')->first();
                            $correct_price = $precio > 0 ? $precio : $request->price;
                            $correct_qty = $cantidad > 0 ? $cantidad : $request->stock;
                            $this->updateAttr($id, $correct_qty, $correct_price, $attr_id->attribute_id, $itemId, $request->manage_stock, $count);
                            $count++;
                        }
                    }
                }
            } else {
                $value = AttributeValue::where('attributes.name', 'Stock')->where('attribute_values.value', 'Automático')->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attr_id', 'attribute_values.id as value_id')->first();
                $this->updateAttr($id, $request->stock, $request->price, $value->attr_id, $value->value_id, $request->manage_stock, 1);
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
        $contenido = $request->input('description');
        $contenidoEscapado = str_replace('"', '&quot;', $contenido);
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                $code = $this->generateSku($request->code);
                $clothing = new ClothingCategory();
                $clothing->name = $request->name;
                $clothing->code = $code;
                $clothing->stock = $request->stock;
                $clothing->description = $contenidoEscapado;
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
                if ($tenantinfo->kind_business == 2 || $tenantinfo->kind_business == 3 || $tenantinfo->tenant === 'muebleriasarchi') {
                    $clothing->can_buy = $request->filled('can_buy') ? 1 : 0;
                }
                $clothing->status = 1;
                $clothing->trending = $request->filled('trending') ? 1 : 0;
                $clothing->meta_keywords = $request->meta_keywords;
                $clothing->manage_stock = $request->manage_stock ? 1 : 0;
                $clothing->is_contra_pedido = $request->is_contra_pedido ? 1 : 0;
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
                    $combos = $request->input('combos', []);
                    if (!empty($combos)) {
                        $this->processCombos($clothingId, $combos, $request->manage_stock);
                    } elseif (!empty($prices_attr)) {
                        // Fall back to legacy precios_attr[] format
                        $count = 1;
                        foreach ($prices_attr as $itemId => $precio) {
                            $cantidad = $cantidades_attr[$itemId];
                            $attr_id = AttributeValue::where('attribute_values.id', $itemId)->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')->select('attributes.id as attribute_id', 'attribute_values.id as value_id')->first();
                            $correct_price = $precio > 0 ? $precio : $request->price;
                            $correct_qty = $cantidad > 0 ? $cantidad : $request->stock;
                            $this->processAttr($clothingId, $correct_qty, $correct_price, $attr_id->attribute_id, $itemId, $request->manage_stock, $count);
                            $count++;
                        }
                    }
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
            $clothing->description = $contenidoEscapado;
            $clothing->price = $request->price;
            $clothing->stock = $request->stock;
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
            $this->processAttr($clothingId, $request->stock, $request->price, $value->attr_id, $value->value_id, $request->manage_stock, 1);
        }
        return $msg;
    }
    public function processAttr($clothingId, $stock, $price = null, $attr_id, $value, $manage_stock, $order)
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
    public function updateAttr($id, $stock, $price = null, $attr_id, $value, $manage_stock, $order)
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
    public function bulkUploadView($id)
    {
        $category = Categories::find($id);
        return view('admin.clothing.bulk-upload', compact('category'));
    }

    public function bulkUpload(Request $request, $id)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:10240']);

        $path    = $request->file('csv_file')->getRealPath();
        $handle  = fopen($path, 'r');
        $rows    = [];
        while (($line = fgetcsv($handle, 0, ',')) !== false) {
            $rows[] = $line;
        }
        fclose($handle);

        $importer = new \App\Imports\ProductsCsvImport();
        $result   = $importer->import($rows);

        $msg = "Creados: {$result['created']} · Omitidos: {$result['skipped']}";
        if (!empty($result['errors'])) {
            $msg .= ' · ' . implode('; ', array_slice($result['errors'], 0, 5));
        }

        return redirect()->back()->with([
            'status' => $msg,
            'icon'   => $result['created'] > 0 ? 'success' : 'warning',
        ]);
    }

    public function isStatus($id, Request $request)
    {
        DB::beginTransaction();
        try {
            ClothingCategory::where('id', $id)->update(['status' => $request->status]);
            DB::commit();
            return response()->json(['message' => 'Estado actualizado con éxito']);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error'], 500);
        }
    }

    public function getVariants($id)
    {
        $clothing     = ClothingCategory::find($id);
        $combinations = VariantCombination::where('clothing_id', $id)
            ->with(['values.attribute', 'values.attributeValue'])
            ->get();

        if ($combinations->isNotEmpty()) {
            $variants = $combinations->map(function ($combo) {
                $label = $combo->values->map(function ($v) {
                    return optional($v->attribute)->name . ': ' . optional($v->attributeValue)->value;
                })->filter()->join(' / ');

                $singleVal = $combo->values->first();
                return [
                    'id'    => $combo->id,
                    'type'  => 'combination',
                    'attr'  => $combo->values->count() === 1 ? optional($singleVal->attribute)->name : '',
                    'val'   => $combo->values->count() === 1 ? optional($singleVal->attributeValue)->value : '',
                    'label' => $label,
                    'stock' => (int) $combo->stock,
                    'price' => (int) $combo->price,
                ];
            });

            return response()->json([
                'has_attr'     => true,
                'base_price'   => $clothing ? (int) $clothing->price : 0,
                'base_stock'   => $clothing ? (int) $clothing->stock : 0,
                'manage_stock' => $clothing ? $clothing->manage_stock : 1,
                'variants'     => $variants,
            ]);
        }

        // Fallback to legacy stocks
        $variants = DB::table('stocks')
            ->join('attributes', 'stocks.attr_id', '=', 'attributes.id')
            ->join('attribute_values', 'stocks.value_attr', '=', 'attribute_values.id')
            ->where('stocks.clothing_id', $id)
            ->whereNotNull('stocks.attr_id')
            ->where('attributes.name', '!=', 'Stock')
            ->select(
                'stocks.id',
                'attributes.name as attr',
                'attribute_values.value as val',
                DB::raw('CAST(stocks.stock AS SIGNED) as stock'),
                DB::raw('CAST(stocks.price AS DECIMAL(10,0)) as price'),
                'stocks.order'
            )
            ->orderBy('stocks.order', 'asc')
            ->get()
            ->map(fn($v) => [
                'id'    => $v->id,
                'type'  => 'stock',
                'attr'  => $v->attr,
                'val'   => $v->val,
                'label' => $v->attr . ': ' . $v->val,
                'stock' => (int) $v->stock,
                'price' => (int) $v->price,
            ]);

        return response()->json([
            'has_attr'     => $variants->isNotEmpty(),
            'base_price'   => $clothing ? (int) $clothing->price : 0,
            'base_stock'   => $clothing ? (int) $clothing->stock : 0,
            'manage_stock' => $clothing ? $clothing->manage_stock : 1,
            'variants'     => $variants,
        ]);
    }

    public function getCombination(Request $request, $clothingId)
    {
        $clothing = ClothingCategory::findOrFail($clothingId);
        $valueIds = array_map('intval', $request->input('values', []));
        sort($valueIds);

        $combination = VariantCombination::where('clothing_id', $clothingId)
            ->with('values')
            ->get()
            ->first(function ($combo) use ($valueIds) {
                $ids = $combo->values->pluck('value_attr')->map('intval')->sort()->values()->toArray();
                return $ids === $valueIds;
            });

        if (!$combination) {
            return response()->json([
                'found'          => false,
                'combination_id' => null,
                'price'          => (int) $clothing->price,
                'stock'          => (int) $clothing->stock,
                'manage_stock'   => $clothing->manage_stock,
            ]);
        }

        return response()->json([
            'found'          => true,
            'combination_id' => $combination->id,
            'price'          => $combination->price > 0 ? (int) $combination->price : (int) $clothing->price,
            'stock'          => (int) $combination->stock,
            'manage_stock'   => $combination->manage_stock,
        ]);
    }

    protected function processCombos($clothingId, array $combos, $manage_stock)
    {
        foreach ($combos as $combo) {
            $valueIds      = array_map('intval', $combo['values'] ?? []);
            if (empty($valueIds)) continue;
            $price         = (float) ($combo['price']          ?? 0);
            $stockVal      = (int)   ($combo['stock']          ?? 0);
            $combinationId = isset($combo['combination_id']) && $combo['combination_id'] !== ''
                ? (int) $combo['combination_id'] : null;
            $effectiveStock = $manage_stock == 1 ? $stockVal : -1;

            if ($combinationId) {
                VariantCombination::where('id', $combinationId)->update([
                    'price'        => $price,
                    'stock'        => $effectiveStock,
                    'manage_stock' => $manage_stock,
                ]);
            } else {
                $combination               = new VariantCombination();
                $combination->clothing_id  = $clothingId;
                $combination->price        = $price;
                $combination->stock        = $effectiveStock;
                $combination->manage_stock = $manage_stock;
                $combination->save();

                foreach ($valueIds as $vid) {
                    $av = AttributeValue::find($vid);
                    if (!$av) continue;
                    $cv                 = new VariantCombinationValue();
                    $cv->combination_id = $combination->id;
                    $cv->attr_id        = $av->attribute_id;
                    $cv->value_attr     = $vid;
                    $cv->save();
                }
            }
        }
    }

    public function quickEdit($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = [];
            if ($request->filled('price')) $data['price'] = $request->input('price');
            if ($request->filled('stock')) $data['stock'] = $request->input('stock');
            if (!empty($data)) ClothingCategory::where('id', $id)->update($data);
            DB::commit();
            $catId = $request->input('category_id');
            if ($catId) {
                foreach ([0, 1, 2] as $s) Cache::forget('clothings_' . $catId . '_' . $s);
            }
            return response()->json(['message' => 'Producto actualizado']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateVariants(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->input('variants', []) as $v) {
                if (($v['type'] ?? 'stock') === 'combination') {
                    VariantCombination::where('id', $v['id'])->update([
                        'stock' => $v['stock'],
                        'price' => $v['price'],
                    ]);
                } else {
                    Stock::where('id', $v['id'])->update([
                        'stock' => $v['stock'],
                        'price' => $v['price'],
                    ]);
                }
            }
            DB::commit();
            $catId = $request->input('category_id');
            if ($catId) {
                foreach ([0, 1, 2] as $s) Cache::forget('clothings_' . $catId . '_' . $s);
            }
            return response()->json(['message' => 'Variantes actualizadas']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $ids    = $request->input('ids', []);
        $action = $request->input('action');
        if (empty($ids)) return response()->json(['error' => 'Sin selección'], 400);
        DB::beginTransaction();
        try {
            match ($action) {
                'activate'   => ClothingCategory::whereIn('id', $ids)->update(['status' => 1]),
                'deactivate' => ClothingCategory::whereIn('id', $ids)->update(['status' => 0]),
                'delete'     => ClothingCategory::whereIn('id', $ids)->delete(),
                default      => throw new \Exception('Acción no válida'),
            };
            DB::commit();
            $catId = $request->input('category_id');
            if ($catId) {
                foreach ([0, 1, 2] as $s) Cache::forget('clothings_' . $catId . '_' . $s);
            }
            return response()->json(['message' => 'Acción ejecutada']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bulkPriceAdjust(Request $request)
    {
        $ids   = $request->input('ids', []);
        $type  = $request->input('type'); // 'increase' | 'discount'
        $pct   = (float) $request->input('pct', 0);
        if (empty($ids) || !in_array($type, ['increase', 'discount']) || $pct <= 0 || $pct > 100) {
            return response()->json(['error' => 'Datos inválidos'], 400);
        }
        $factor = $type === 'increase' ? (1 + $pct / 100) : (1 - $pct / 100);
        DB::beginTransaction();
        try {
            DB::table('clothing')->whereIn('id', $ids)
                ->update(['price' => DB::raw("ROUND(price * {$factor})")]);
            DB::table('stocks')->whereIn('clothing_id', $ids)->where('price', '>', 0)
                ->update(['price' => DB::raw("ROUND(price * {$factor})")]);
            DB::commit();
            $catId = $request->input('category_id');
            if ($catId) {
                foreach ([0, 1, 2] as $s) Cache::forget('clothings_' . $catId . '_' . $s . '_');
            }
            return response()->json(['message' => 'Precios actualizados']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
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
    public function getProductsToSelect(Request $request)
    {
        $search = $request->input('search'); // Captura el término de búsqueda

        $clothings = ClothingCategory::leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
            ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
            ->leftJoin('clothing_details', 'clothing.id', 'clothing_details.clothing_id')
            ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
            ->select(
                'clothing.id as service_id',
                DB::raw('CONCAT(clothing.name, " (", categories.name, ")") as name'),
                DB::raw('CONCAT("/", clothing.id, "/", categories.id, "/") as url')
            )
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('clothing.name', 'like', "%{$search}%");
                }
            })
            ->where('clothing.status', 1)
            ->groupBy(
                'clothing.id',
                'categories.id',
                'clothing.name',
                'categories.name'
            )->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
            ->orderBy('clothing.casa', 'asc')
            ->orderBy('clothing.name', 'asc')
            ->get();

        return response()->json($clothings);
    }
    public function reportCatProd($type)
    {
        $clothings = Cache::remember('clothings_report', $this->expirationTime, function () {
            return DB::table('clothing')
                ->where('clothing.status', 1)
                ->leftJoin('stocks', 'clothing.id', '=', 'stocks.clothing_id')
                ->leftJoin('attributes', 'stocks.attr_id', '=', 'attributes.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                            SELECT MIN(id) FROM product_images
                            WHERE product_images.clothing_id = clothing.id
                        )');
                })
                ->select(
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.code as code',
                    'clothing.status as status',
                    'clothing.manage_stock as manage_stock',
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('COALESCE((SELECT SUM(vc.stock) FROM variant_combinations vc WHERE vc.clothing_id = clothing.id AND vc.stock >= 0), SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END)) as total_stock'),
                    DB::raw('GROUP_CONCAT(COALESCE(attributes.name, "")) AS available_attr'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('GROUP_CONCAT(COALESCE(stocks.attr_id, "")) AS attr_id_per_size'),
                    'product_images.image as image'
                )
                ->groupBy(
                    'clothing.id',
                    'clothing.casa',
                    'clothing.mayor_price',
                    'clothing.discount',
                    'clothing.code',
                    'clothing.status',
                    'clothing.manage_stock',
                    'clothing.name',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'product_images.image'
                )
                ->orderBy('name', 'asc')
                ->get();
        });
        $categories = Categories::get();

        return view('admin.reports.catprod', compact('clothings', 'categories', 'type'));
    }
}
