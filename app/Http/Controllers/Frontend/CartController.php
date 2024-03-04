<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ClothingCategory;
use App\Models\MetaTags;
use App\Models\TenantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\URL;

class CartController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
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
                        return response()->json(['status' => 'Se ha agregado la prenda al carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber]);
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
                    return response()->json(['status' => 'Se ha agregado la prenda al carrito', 'icon' => 'success', 'cartNumber' => $newCartNumber]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'Ocurrió un error al agregar la prenda al carrito', 'icon' => 'success']);
        }
    }
    public function viewCart()
    {
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
                    'clothing.description as description',
                    'clothing.price as price',
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
                    'clothing.description',
                    'clothing.price',
                    'clothing.status',
                    'clothing.discount',
                    'sizes.size',
                    'sizes.id',
                    'carts.quantity',
                    'stocks.stock',
                    'product_images.image'
                )
                ->get();
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
                    'clothing.description as description',
                    'clothing.price as price',
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
                    'clothing.description',
                    'clothing.price',
                    'clothing.discount',
                    'clothing.status',
                    'sizes.size',
                    'sizes.id',
                    'carts.quantity',
                    'stocks.stock',
                    'product_images.image'
                )
                ->get();
        }

        $tags = MetaTags::where('section', 'Carrito')->get();
        $tenantinfo = TenantInfo::first();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " .$tenantinfo->title);
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
            $descuentoPorcentaje = $item->discount;
            // Calcular el descuento
            $descuento = ($precio * $descuentoPorcentaje) / 100;
            $you_save = $you_save + $descuento;
            // Calcular el precio con el descuento aplicado
            $precioConDescuento = $precio - $descuento;
            $cloth_price += $precioConDescuento * $item->quantity;
        }

        $iva = $cloth_price * 0.13;
        $total_price = $cloth_price + $iva;

        return view('frontend.view-cart', compact('cart_items', 'name', 'cloth_price', 'iva', 'total_price','you_save'));
    }
    public function delete($id, $size_id)
    {
        DB::beginTransaction();

        try {
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
            $session_id = session()->get('session_id');

            if (Auth::check()) {

                if (Cart::where('clothing_id', $clothing_id)->where('user_id', Auth::id())->exists()) {
                    $cartitem = Cart::where('clothing_id', $clothing_id)->where('user_id', Auth::id())->first();
                    $cartitem->quantity = $quantity;
                    $cartitem->update();
                    DB::commit();
                    return response()->json(['status' => 'Exito']);
                }
            } else {
                if (Cart::where('clothing_id', $clothing_id)->where('session_id', $session_id)->exists()) {
                    $cartitem = Cart::where('clothing_id', $clothing_id)->where('session_id', $session_id)->first();
                    $cartitem->quantity = $quantity;
                    $cartitem->update();
                    DB::commit();
                    return response()->json(['status' => 'Exito']);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
