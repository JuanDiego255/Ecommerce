<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tenantinfo = TenantInfo::first();
            if ($request->code) {
                $code = $request->code;
                $cloth_check = ClothingCategory::where('code', $code)->first();
                $size_id = $request->size_id;
                if(isset($tenantinfo->manage_size) && $tenantinfo->manage_size == 0){
                    $size = Size::where('size','N/A')->first();
                    $size_id = $size->id;
                }
                if ($cloth_check) {
                    $stock = Stock::where('clothing_id', $cloth_check->id)
                        ->where('size_id', $size_id)
                        ->first();
                    if ($stock->stock == 0) {
                        return response()->json(['status' => 'El producto no cuenta con inventario', 'icon' => 'warning']);
                    }

                    if (Cart::where('clothing_id', $cloth_check->id)
                        ->where('user_id', null)
                        ->where('session_id', null)
                        ->where('size_id', $request->size_id)
                        ->where('sold', 0)->first()
                    ) {
                        return response()->json(['status' => 'El producto ya existe en la tabla', 'icon' => 'warning']);
                    } else {                      

                        $cart_item = new Cart();
                        $cart_item->user_id = null;
                        $cart_item->clothing_id = $cloth_check->id;
                        $cart_item->quantity = 1;
                        $cart_item->size_id = $size_id;
                        $cart_item->sold = 0;
                        $cart_item->save();

                        DB::commit();
                        return response()->json(['status' => 'success', 'icon' => 'success']);
                    }
                } else {
                }
            } else {
                $clothing_id = $request->clothing_id;
                $quantity = $request->quantity;
                $size_id = $request->size_id;

                if (Auth::check()) {
                    $cloth_check = ClothingCategory::where('id', $clothing_id)->exists();
                    if ($cloth_check) {
                        if (Cart::where('clothing_id', $clothing_id)
                            ->where('user_id', Auth::id())
                            ->where('sold', 0)
                            ->where('size_id', $size_id)->first()
                        ) {
                            return response()->json(['status' => 'El producto ya existe en el carrito', 'icon' => 'warning']);
                        } else {
                            $cart_item = new Cart();
                            $cart_item->user_id = Auth::id();
                            $cart_item->clothing_id = $clothing_id;
                            $cart_item->quantity = $quantity;
                            $cart_item->size_id = $size_id;
                            $cart_item->sold = 0;
                            $cart_item->save();
                            $newCartNumber = count(Cart::where('user_id', Auth::id())->where('sold', 0)->get());

                            view()->share([
                                'cartNumber' => $newCartNumber
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
                    if (Cart::where('clothing_id', $clothing_id)
                        ->where('session_id', $session_id)
                        ->where('sold', 0)
                        ->where('size_id', $size_id)->first()
                    ) {
                        return response()->json(['status' => 'El producto ya existe en el carrito', 'icon' => 'warning']);
                    } else {
                        $cart_item = new Cart();
                        $cart_item->session_id = $session_id;
                        $cart_item->clothing_id = $clothing_id;
                        $cart_item->quantity = $quantity;
                        $cart_item->size_id = $size_id;
                        $cart_item->sold = 0;
                        $cart_item->save();

                        // Obtener el número de elementos en el carrito anónimo
                        $newCartNumber = count(Cart::where('session_id', $session_id)->where('sold', 0)->get());

                        DB::commit();
                        return response()->json(['status' => 'Se ha agregado el artículo al carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber]);
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'Ocurrió un error al agregar la artículo al carrito ' . $th->getMessage(), 'icon' => 'success']);
        }
    }
    public function viewCart()
    {
        $tenantinfo = TenantInfo::first();
        $cart_items = Cache::remember('cart_items', $this->expirationTime, function () {
            if (Auth::check()) {
                $userId = Auth::id();
                $cart_items = Cart::where('carts.user_id', $userId)
                    ->where('carts.session_id', null)
                    ->where('carts.sold', 0)
                    ->join('users', 'carts.user_id', 'users.id')
                    ->join('stocks', function ($join) {
                        $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                            ->on('carts.size_id', '=', 'stocks.size_id');
                    })
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->join('sizes', 'carts.size_id', 'sizes.id')
                    ->leftJoin('product_images', function ($join) {
                        $join->on('clothing.id', '=', 'product_images.clothing_id')
                            ->whereRaw('product_images.id = (
                            SELECT MIN(id) FROM product_images 
                            WHERE product_images.clothing_id = clothing.id
                        )');
                    })
                    ->select(
                        'clothing.id as id',
                        'clothing.name as name',
                        'clothing.casa as casa',
                        'clothing.description as description',
                        'clothing.price as price',
                        'clothing.mayor_price as mayor_price',
                        'clothing.discount as discount',
                        'clothing.status as status',
                        'sizes.size as size',
                        'sizes.id as size_id',
                        'carts.quantity as quantity',
                        'stocks.stock as stock',
                        DB::raw('IFNULL(product_images.image, "") as image') // Obtener la primera imagen del producto
                    )
                    ->groupBy(
                        'clothing.id',
                        'clothing.name',
                        'clothing.casa',
                        'clothing.description',
                        'clothing.price',
                        'clothing.mayor_price',
                        'clothing.status',
                        'clothing.discount',
                        'sizes.size',
                        'sizes.id',
                        'carts.quantity',
                        'stocks.stock',
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
                    ->join('stocks', function ($join) {
                        $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                            ->on('carts.size_id', '=', 'stocks.size_id');
                    })
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->join('sizes', 'carts.size_id', 'sizes.id')
                    ->leftJoin('product_images', function ($join) {
                        $join->on('clothing.id', '=', 'product_images.clothing_id')
                            ->whereRaw('product_images.id = (
                SELECT MIN(id) FROM product_images 
                WHERE product_images.clothing_id = clothing.id
            )');
                    })
                    ->select(
                        'clothing.id as id',
                        'clothing.name as name',
                        'clothing.casa as casa',
                        'clothing.description as description',
                        'clothing.price as price',
                        'clothing.mayor_price as mayor_price',
                        'clothing.discount as discount',
                        'clothing.status as status',
                        'sizes.size as size',
                        'sizes.id as size_id',
                        'carts.quantity as quantity',
                        'stocks.stock as stock',
                        DB::raw('IFNULL(product_images.image, "") as image') // Obtener la primera imagen del producto
                    )
                    ->groupBy(
                        'clothing.id',
                        'clothing.name',
                        'clothing.casa',
                        'clothing.description',
                        'clothing.price',
                        'clothing.mayor_price',
                        'clothing.discount',
                        'clothing.status',
                        'sizes.size',
                        'sizes.id',
                        'carts.quantity',
                        'stocks.stock',
                        'product_images.image'
                    )
                    ->get();
                return $cart_items;
            }
        });

        $tags = MetaTags::where('section', 'Carrito')->get();
        $tenantinfo = TenantInfo::first();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        if (count($cart_items) == 0) {
            return redirect()->back()->with(['status' => 'NO HAY PRODUCTOS EN EL CARRITO :(', 'icon' => 'warning']);
        }

        $name = Auth::user()->name ?? null;

        $cloth_price = 0;
        $you_save = 0;
        foreach ($cart_items as $item) {
            $precio = $item->price;
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

        return view('frontend.view-cart', compact('cart_items', 'name', 'cloth_price', 'iva', 'total_price', 'you_save'));
    }
    public function delete($id, $size_id, Request $request)
    {
        DB::beginTransaction();

        try {
            $no_cart = $request->no_cart;

            if ($no_cart != 1) {
                $session_id = session()->get('session_id');
                if (Auth::check()) {

                    if (Cart::where('clothing_id', $id)
                        ->where('user_id', Auth::id())
                        ->where('sold', 0)
                        ->where('size_id', $size_id)->exists()
                    ) {
                        $cartitem = Cart::where('clothing_id', $id)
                            ->where('user_id', Auth::id())
                            ->where('sold', 0)
                            ->where('size_id', $size_id)->first();
                        $cartitem->delete();
                        DB::commit();
                        if (count(Cart::where('user_id', Auth::id())
                            ->where('sold', 0)->get()) == 0) {
                            return redirect('/')->with(['status' => 'Se ha eliminado el último artículo del carrito', 'icon' => 'success'])->with(['alert' => 'error']);
                        }
                        return redirect()->back()->with(['status' => 'Se ha eliminado el artículo del carrito', 'icon' => 'success'])->with(['alert' => 'error']);
                    }
                } else {
                    if (Cart::where('clothing_id', $id)
                        ->where('session_id', $session_id)
                        ->where('sold', 0)
                        ->where('size_id', $size_id)->exists()
                    ) {
                        $cartitem = Cart::where('clothing_id', $id)
                            ->where('session_id', $session_id)
                            ->where('sold', 0)
                            ->where('size_id', $size_id)->first();
                        $cartitem->delete();
                        DB::commit();
                        if (count(Cart::where('session_id', $session_id)
                            ->where('sold', 0)->get()) == 0) {
                            return redirect('/')->with(['status' => 'Se ha eliminado el último artículo del carrito', 'icon' => 'success'])->with(['alert' => 'error']);
                        }
                        return redirect()->back()->with(['status' => 'Se ha eliminado el artículo del carrito', 'icon' => 'success'])->with(['alert' => 'error']);
                    }
                }
            } else {
                if (Cart::where('clothing_id', $id)
                    ->where('user_id', null)
                    ->where('session_id', null)
                    ->where('sold', 0)
                    ->where('size_id', $size_id)->exists()
                ) {
                    $cartitem = Cart::where('clothing_id', $id)
                        ->where('user_id', null)
                        ->where('session_id', null)
                        ->where('sold', 0)
                        ->where('size_id', $size_id)->first();
                    $cartitem->delete();
                    DB::commit();
                    if (count(Cart::where('user_id', Auth::id())
                        ->where('sold', 0)->get()) == 0) {
                        return redirect()->back();
                    }
                    return redirect()->back();
                }
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
            $clothing_id = $request->clothing_id;
            $quantity = $request->quantity;
            $size = $request->size;
            $kind_of = $request->kind_of;
            $session_id = session()->get('session_id');
            if ($kind_of != "F") {
                if (Auth::check()) {

                    if (Cart::where('clothing_id', $clothing_id)->where('user_id', Auth::id())->where('sold', 0)->exists()) {
                        $cartitem = Cart::where('clothing_id', $clothing_id)
                            ->where('user_id', Auth::user()->id)
                            ->where('sold', 0)
                            ->where('size_id', $size)
                            ->first();
                        $cartitem->quantity = $quantity;
                        $cartitem->update();
                        DB::commit();
                        return response()->json(['status' => $kind_of]);
                    }
                } else {
                    if (Cart::where('clothing_id', $clothing_id)->where('session_id', $session_id)->exists()) {
                        $cartitem = Cart::where('clothing_id', $clothing_id)
                            ->where('session_id', $session_id)
                            ->where('sold', 0)
                            ->where('size_id', $size)
                            ->first();
                        $cartitem->quantity = $quantity;
                        $cartitem->update();
                        DB::commit();
                        return response()->json(['status' => 'Exito']);
                    }
                }
            } else {
                if (Cart::where('clothing_id', $clothing_id)
                    ->where('user_id', null)
                    ->where('session_id', null)
                    ->where('size_id', $size)
                    ->where('sold', 0)->exists()
                ) {
                    $cartitem = Cart::where('clothing_id', $clothing_id)
                        ->where('user_id', null)
                        ->where('session_id', null)
                        ->where('size_id', $size)
                        ->where('sold', 0)
                        ->first();
                    $cartitem->quantity = $quantity;
                    $cartitem->update();
                    DB::commit();
                    return response()->json(['status' => $clothing_id]);
                }
            }
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()]);
        }
    }
}
