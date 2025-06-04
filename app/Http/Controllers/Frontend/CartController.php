<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AttributeValueCar;
use App\Models\Cart;
use App\Models\ClothingCategory;
use App\Models\MetaTags;
use App\Models\Size;
use App\Models\Stock;
use App\Models\TenantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class CartController extends Controller
{
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60; // Por ejemplo, 60 minutos
    }
    public function checkCartItem($attributes, $cloth_id, $type, $user)
    {
        $found = true;
        $parsedAttributes = [];
        foreach ($attributes as $attribute) {
            [$value_attr, $attr_id] = explode('-', $attribute);
            $parsedAttributes[$attr_id] = $value_attr;
        }

        switch ($type) {
            case 'S':
                $cartItems = Cart::where('clothing_id', $cloth_id)->where('session_id', $user)->where('sold', 0)->get();
                break;
            case 'C':
                $cartItems = Cart::where('clothing_id', $cloth_id)->where('user_id', null)->where('session_id', null)->where('sold', 0)->get();
                break;
            default:
                $cartItems = Cart::where('clothing_id', $cloth_id)->where('user_id', $user)->where('sold', 0)->get();
        }

        foreach ($cartItems as $cartItem) {
            $cartAttributes = DB::table('attribute_value_cars')
                ->where('cart_id', $cartItem->id)
                ->get()
                ->pluck('value_attr', 'attr_id')
                ->toArray();

            $match = true;
            foreach ($parsedAttributes as $attr_id => $value_attr) {
                if (!isset($cartAttributes[$attr_id]) || $cartAttributes[$attr_id] != $value_attr) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                $found = false;
                break; // Si se encuentra una coincidencia, no es necesario seguir buscando
            }
        }

        // Retorna true si no se encontró una coincidencia, false en caso contrario
        return $found;
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $attributes = json_decode($request->input('attributes'), true);
            $prefix_cart = "CARTID";
            if ($request->code) {
                $code = $request->code;
                $updateId = $request->updateId;
                $cloth_check = ClothingCategory::where('code', $code)->first();

                if ($cloth_check) {
                    $found = $this->checkCartItem($attributes, $cloth_check->id, 'C', '');
                    if (!$found) {
                        return response()->json(['status' => 'El producto ya existe en el carrito', 'icon' => 'warning']);
                    } else {
                        $cart_item = new Cart();
                        $cart_item->user_id = null;
                        $cart_item->clothing_id = $cloth_check->id;
                        $cart_item->quantity = $request->quantity != null ? $request->quantity : 1;
                        $cart_item->sold = $request->updateId != 0 ? 1 : 0;
                        $cart_item->buy_id = $request->updateId != 0 ? $updateId : null;
                        $cart_item->save();
                        $cart_id = $cart_item->id;
                        foreach ($attributes as $attr) {
                            [$value_attr, $attr_id_val, $cloth_id] = explode('-', $attr);

                            $attr_db = new AttributeValueCar();
                            $attr_db->cart_id = $cart_id;
                            $attr_db->attr_id = $attr_id_val;
                            $attr_db->value_attr = $value_attr;
                            $attr_db->save();
                        }

                        $newCartNumber = count(Cart::where('user_id', Auth::id())->where('sold', 0)->get());

                        view()->share([
                            'cartNumber' => $newCartNumber,
                        ]);
                        DB::commit();
                        return response()->json(['status' => 'Se ha agregado el artículo al carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber]);
                    }
                } else {
                }
            } else {
                $clothing_id = $request->clothing_id;
                $quantity = $request->quantity;
                if (Auth::check()) {
                    $cloth_check = ClothingCategory::where('id', $clothing_id)->exists();
                    if ($cloth_check) {
                        $found = $this->checkCartItem($attributes, $clothing_id, 'U', Auth::id());
                        if (!$found) {
                            return response()->json(['status' => 'El producto ya existe en el carrito', 'icon' => 'warning']);
                        } else {
                            $cart_item = new Cart();
                            $cart_item->user_id = Auth::id();
                            $cart_item->clothing_id = $clothing_id;
                            $cart_item->quantity = $quantity;
                            $cart_item->sold = 0;
                            $cart_item->unique_cart_id = $prefix_cart . Auth::id();
                            $cart_item->save();
                            $cart_id = $cart_item->id;
                            foreach ($attributes as $attr) {
                                [$value_attr, $attr_id_val, $cloth_id] = explode('-', $attr);

                                $attr_db = new AttributeValueCar();
                                $attr_db->cart_id = $cart_id;
                                $attr_db->attr_id = $attr_id_val;
                                $attr_db->value_attr = $value_attr;
                                $attr_db->save();
                            }
                            $newCartNumber = count(Cart::where('user_id', Auth::id())->where('sold', 0)->get());

                            view()->share([
                                'cartNumber' => $newCartNumber,
                            ]);
                            DB::commit();
                            return response()->json(['status' => 'Se ha agregado el artículo al carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber]);
                        }
                    } else {
                    }
                } else {
                    $session_id = session()->get('session_id');

                    if (!$session_id) {
                        $session_id = uniqid(); // Genera un identificador único temporal
                        session()->put('session_id', $session_id);
                    }
                    // Verificar si el producto ya está en el carrito del usuario anónimo
                    $found = $this->checkCartItem($attributes, $clothing_id, 'S', $session_id);
                    if (!$found) {
                        return response()->json(['status' => 'El producto ya existe en el carrito', 'icon' => 'warning']);
                    } else {
                        $cart_item = new Cart();
                        $cart_item->session_id = $session_id;
                        $cart_item->clothing_id = $clothing_id;
                        $cart_item->quantity = $quantity;
                        $cart_item->sold = 0;
                        $cart_item->unique_cart_id = $prefix_cart . $session_id;
                        $cart_item->save();
                        $cart_id = $cart_item->id;
                        foreach ($attributes as $attr) {
                            [$value_attr, $attr_id_val, $cloth_id] = explode('-', $attr);

                            $attr_db = new AttributeValueCar();
                            $attr_db->cart_id = $cart_id;
                            $attr_db->attr_id = $attr_id_val;
                            $attr_db->value_attr = $value_attr;
                            $attr_db->save();
                        }

                        // Obtener el número de elementos en el carrito anónimo
                        $newCartNumber = count(Cart::where('session_id', $session_id)->where('sold', 0)->get());

                        DB::commit();
                        return response()->json(['status' => 'Se ha agregado el artículo al carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber]);
                    }
                }
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(['status' => 'Ocurrió un error al agregar la artículo al carrito ' . $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function viewCart($unique_cart_id = null)
    {
        $tenantinfo = TenantInfo::first();
        $cart_items = $this->getCartItems($unique_cart_id);

        $tags = MetaTags::where('section', 'Carrito')->get();
        $tenantinfo = TenantInfo::first();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . ' - ' . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        if (count($cart_items) == 0) {
            return redirect()
                ->back()
                ->with(['status' => 'NO HAY PRODUCTOS EN EL CARRITO :(', 'icon' => 'warning']);
        }

        $name = Auth::user()->name ?? null;

        $cloth_price = 0;
        $you_save = 0;
        foreach ($cart_items as $item) {
            $precio = $item->price;
            if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $item->stock_price > 0) {
                $precio = $item->stock_price;
            }
            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                $precio = $item->mayor_price;
            }
            $descuentoPorcentaje = $item->discount;
            // Calcular el descuento
            $descuento = ($precio * $descuentoPorcentaje) / 100;
            // Calcular el precio con el descuento aplicado
            $precioConDescuento = $precio - $descuento;
            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                $precio = $item->mayor_price;
            }
            $descuentoPorcentaje = $item->discount;
            // Calcular el descuento
            $descuento = ($precio * $descuentoPorcentaje) / 100;

            $you_save += $descuento * $item->quantity;
            // Calcular el precio con el descuento aplicado
            $precioConDescuento = $precio - $descuento;
            $cloth_price += $precioConDescuento * $item->quantity;
        }

        $iva = $cloth_price * $tenantinfo->iva;
        $total_price = $cloth_price + $iva;
        switch ($tenantinfo->kind_business) {
            default:
                if ($tenantinfo->kind_of_features == 1) {
                    return view('frontend.design_ecommerce.view-cart', compact('cart_items', 'name', 'cloth_price', 'iva', 'total_price', 'you_save'));
                }
                return view('frontend.view-cart', compact('cart_items', 'name', 'cloth_price', 'iva', 'total_price', 'you_save'));
                break;
        }
    }
    public function delete($id, Request $request)
    {
        DB::beginTransaction();

        try {
            $session_id = session()->get('session_id');
            $esVentaInterna = false;
            if (Auth::check()) {
                $buy_in = Cart::where('id', $id)->first();
                if ($buy_in->user_id == null && $buy_in->session_id == null) {
                    $esVentaInterna = true;
                }
                Cart::destroy($id);
                DB::commit();
                $newCartNumber = count(Cart::where('user_id', Auth::id())->where('sold', 0)->get());
                if (count(Cart::where('user_id', Auth::id())->where('sold', 0)->get()) == 0 && $esVentaInterna != true) {
                    return response()->json(['status' => 'Se ha eliminado el último artículo del carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber, 'refresh' => true]);
                }
                return response()->json(['status' => 'Se ha eliminado el artículo del carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber, 'refresh' => false]);
            } else {
                Cart::destroy($id);
                DB::commit();
                $newCartNumber = count(Cart::where('session_id', $session_id)->where('sold', 0)->get());
                if (count(Cart::where('session_id', $session_id)->where('sold', 0)->get()) == 0) {
                    return response()->json(['status' => 'Se ha eliminado el último artículo del carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber, 'refresh' => true]);
                }
                return response()->json(['status' => 'Se ha eliminado el artículo del carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber, 'refresh' => false]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'Ocurrió un error al eliminar la prenda al carrito', 'icon' => 'success']);
        }
    }
    public function updateQuantity(Request $request)
    {
        DB::beginTransaction();
        try {
            $quantity = $request->quantity;
            $cart_id = $request->cart_id;
            $cartitem = Cart::find($cart_id);
            $cartitem->quantity = $quantity;
            $cartitem->update();
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()]);
        }
    }
    public function getCartItems($unique_cart_id)
    {
        $cart_items = Cache::remember('cart_items', $this->expirationTime, function () use ($unique_cart_id) {
            if ($unique_cart_id != "cnormal-in") {
                $cart_items = Cart::where('carts.unique_cart_id', $unique_cart_id)
                    ->where('carts.sold', 0)
                    ->leftJoin('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                    ->leftJoin('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                    ->leftJoin('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                    ->leftJoin('stocks', function ($join) {
                        $join->on('carts.clothing_id', '=', 'stocks.clothing_id')->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr')->where('stocks.price', '!=', 0);
                    })
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->leftJoin('product_images', function ($join) {
                        $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images
                                WHERE product_images.clothing_id = clothing.id
                            )');
                    })
                    ->select(
                        'clothing.id as id',
                        'clothing.name as name',
                        'clothing.casa as casa',
                        'clothing.description as description',
                        'clothing.mayor_price as mayor_price',
                        'clothing.discount as discount',
                        'clothing.status as status',
                        'carts.quantity as quantity',
                        'carts.id as cart_id',
                        'carts.unique_cart_id as unique_cart_id',
                        'attributes.name as name_attr',
                        'attribute_values.value as value',
                        DB::raw('COALESCE(stocks.price, clothing.price) as price'),
                        DB::raw('COALESCE(stocks.stock, clothing.stock) as stock'),
                        DB::raw('(
                            SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                            FROM attribute_value_cars
                            JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                            JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                            WHERE attribute_value_cars.cart_id = carts.id
                        ) as attributes_values'),
                        DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
                    )
                    ->groupBy(
                        'clothing.id',
                        'clothing.price',
                        'clothing.stock',
                        'clothing.name',
                        'clothing.casa',
                        'clothing.description',
                        'stocks.price',
                        'stocks.stock',
                        'clothing.mayor_price',
                        'attributes.name',
                        'attribute_values.value',
                        'clothing.status',
                        'clothing.discount',
                        'carts.quantity',
                        'carts.id',
                        'carts.unique_cart_id',
                        'product_images.image'
                    )
                    ->get();

                return $cart_items;
            }
            if (Auth::check()) {
                $userId = Auth::id();
                $cart_items = Cart::where('carts.user_id', $userId)
                    ->where('carts.session_id', null)
                    ->where('carts.sold', 0)
                    ->join('users', 'carts.user_id', 'users.id')
                    ->leftJoin('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                    ->leftJoin('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                    ->leftJoin('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')

                    ->leftJoin('stocks', function ($join) {
                        $join->on('carts.clothing_id', '=', 'stocks.clothing_id')->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr')
                            ->where('stocks.price', '!=', 0);
                    })
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->leftJoin('product_images', function ($join) {
                        $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images
                                WHERE product_images.clothing_id = clothing.id
                            )');
                    })
                    ->select(
                        'clothing.id as id',
                        'clothing.name as name',
                        'clothing.casa as casa',
                        'clothing.description as description',
                        'clothing.mayor_price as mayor_price',
                        'clothing.discount as discount',
                        'carts.unique_cart_id as unique_cart_id',
                        'clothing.status as status',
                        'carts.quantity as quantity',
                        'carts.id as cart_id',
                        'attributes.name as name_attr',
                        'attribute_values.value as value',
                        DB::raw('COALESCE(stocks.price, clothing.price) as price'),
                        DB::raw('COALESCE(stocks.stock, clothing.stock) as stock'),
                        DB::raw('(
                            SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                            FROM attribute_value_cars
                            JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                            JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                            WHERE attribute_value_cars.cart_id = carts.id
                        ) as attributes_values'),
                        DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
                    )
                    ->groupBy(
                        'clothing.id',
                        'clothing.price',
                        'clothing.stock',
                        'clothing.name',
                        'clothing.casa',
                        'clothing.description',
                        'stocks.price',
                        'stocks.stock',
                        'clothing.mayor_price',
                        'attributes.name',
                        'attribute_values.value',
                        'clothing.status',
                        'clothing.discount',
                        'carts.quantity',
                        'carts.id',
                        'carts.unique_cart_id',
                        'product_images.image'
                    )
                    ->get();
                // Resto del código para obtener los artículos del carrito para usuarios autenticados
                return $cart_items;
            } else {
                $session_id = session()->get('session_id');
                $cart_items = Cart::where('carts.session_id', $session_id)
                    ->where('carts.user_id', null)
                    ->where('carts.sold', 0)
                    ->leftJoin('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                    ->leftJoin('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                    ->leftJoin('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                    ->leftJoin('stocks', function ($join) {
                        $join->on('carts.clothing_id', '=', 'stocks.clothing_id')->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr')->where('stocks.price', '!=', 0);
                    })
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->leftJoin('product_images', function ($join) {
                        $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images
                                WHERE product_images.clothing_id = clothing.id
                            )');
                    })
                    ->select(
                        'clothing.id as id',
                        'clothing.name as name',
                        'clothing.casa as casa',
                        'clothing.description as description',
                        'carts.unique_cart_id as unique_cart_id',
                        'clothing.mayor_price as mayor_price',
                        'clothing.discount as discount',
                        'clothing.status as status',
                        'carts.quantity as quantity',
                        'carts.id as cart_id',
                        'attributes.name as name_attr',
                        'attribute_values.value as value',
                        DB::raw('COALESCE(stocks.price, clothing.price) as price'),
                        DB::raw('COALESCE(stocks.stock, clothing.stock) as stock'),
                        DB::raw('(
                            SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                            FROM attribute_value_cars
                            JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                            JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                            WHERE attribute_value_cars.cart_id = carts.id
                        ) as attributes_values'),
                        DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
                    )
                    ->groupBy(
                        'clothing.id',
                        'clothing.price',
                        'clothing.stock',
                        'clothing.name',
                        'clothing.casa',
                        'clothing.description',
                        'stocks.price',
                        'stocks.stock',
                        'clothing.mayor_price',
                        'attributes.name',
                        'attribute_values.value',
                        'clothing.status',
                        'clothing.discount',
                        'carts.quantity',
                        'carts.id',
                        'carts.unique_cart_id',
                        'product_images.image'
                    )
                    ->get();

                return $cart_items;
            }
        });
        return $cart_items;
    }
    public function getCart()
    {
        $cart_items = $this->getCartItems("cnormal-in");
        return response()->json($cart_items);
    }
}
