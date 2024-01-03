<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AddressUser;
use App\Models\Buy;
use App\Models\BuyDetail;
use App\Models\Cart;
use App\Models\MetaTags;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\URL;

class CheckOutController extends Controller
{
    //

    public function index()
    {
        $user_info = null;
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->where('carts.sold', 0)
                ->join('clothing', 'carts.clothing_id', 'clothing.id')
                ->join('sizes', 'carts.size_id', 'sizes.id')
                ->select(

                    'clothing.id as id',
                    'clothing.name as name',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.image as image',
                    'clothing.status as status',
                    'sizes.size as size',
                    'carts.quantity as quantity'

                )->get();
            $cloth_price = 0;
            foreach ($cartItems as $item) {
                $cloth_price += $item->price * $item->quantity;
            }
            $user_info = AddressUser::where('user_id',Auth::user()->id)
            ->where('status',1)->first();
            $iva = $cloth_price * 0.13;
            $total_price = $cloth_price + $iva;
        } else {
            $session_id = session()->get('session_id');
            $cartItems = Cart::where('session_id', $session_id)
                ->where('carts.sold', 0)
                ->join('clothing', 'carts.clothing_id', 'clothing.id')
                ->join('sizes', 'carts.size_id', 'sizes.id')
                ->select(

                    'clothing.id as id',
                    'clothing.name as name',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.image as image',
                    'clothing.status as status',
                    'sizes.size as size',
                    'carts.quantity as quantity'

                )->get();
            $cloth_price = 0;
            foreach ($cartItems as $item) {
                $cloth_price += $item->price * $item->quantity;
            }

            $iva = $cloth_price * 0.13;
            $total_price = $cloth_price + $iva;
        }
        $tags = MetaTags::where('section', 'Checkout')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        return view('frontend.checkout', compact('cartItems', 'iva', 'total_price', 'cloth_price','user_info'));
    }

    public function payment(Request $request)
    {
        try {
            DB::beginTransaction();
            if (Auth::check()) {
                $cartItems = Cart::where('user_id', Auth::user()->id)->where('sold', 0)
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->join('sizes', 'carts.size_id', 'sizes.id')
                    ->select(
                        'clothing.id as clothing_id',
                        'clothing.name as name',
                        'clothing.description as description',
                        'clothing.price as price',
                        'clothing.image as image',
                        'clothing.status as status',
                        'sizes.size as size',
                        'carts.quantity as quantity',
                        'carts.size_id as size_id'
                    )->get();
                $cloth_price = 0;

                foreach ($cartItems as $cart) {
                    $cloth_price += $cart->price * $cart->quantity;
                }
                $iva = $cloth_price * 0.13;
                $total_price = $cloth_price + $iva;

                $buy = new Buy();
                if ($request->hasFile('image')) {
                    $buy->image = $request->file('image')->store('uploads', 'public');
                }
                $buy->user_id =  Auth::user()->id;
                $buy->address =  $request->address;
                $buy->address_two =  $request->address_two;
                $buy->city =  $request->city;
                $buy->province =  $request->province;
                $buy->country =  $request->country;
                $buy->postal_code =  $request->postal_code;
                $buy->total_iva =  $iva;
                $buy->total_buy =  $total_price;
                $buy->delivered = 0;
                $buy->approved = 0;
                $buy->cancel_buy = 0;
                $buy->save();
                $buy_id = $buy->id;

                foreach ($cartItems as $cart) {
                    $buy_detail = new BuyDetail();
                    $buy_detail->buy_id = $buy_id;
                    $buy_detail->clothing_id = $cart->clothing_id;
                    $buy_detail->size_id = $cart->size_id;
                    $buy_detail->total = ($cart->price * $cart->quantity) + ($cart->price * 0.13);
                    $buy_detail->iva = $cart->price * 0.13;
                    $buy_detail->quantity = $cart->quantity;
                    $buy_detail->cancel_item = 0;
                    $buy_detail->save();
                    $stock = Stock::where('clothing_id', $cart->clothing_id)
                        ->where('size_id', $cart->size_id)
                        ->first();
                    Stock::where('clothing_id', $cart->clothing_id)
                        ->where('size_id', $cart->size_id)
                        ->update(['stock' => ($stock->stock - $cart->quantity)]);
                }

                Cart::where('user_id', Auth::user()->id)->where('sold', 0)->update(['sold' => 1]);
                DB::commit();
            } else {
                $session_id = session()->get('session_id');
                $cartItems = Cart::where('session_id', $session_id)->where('sold', 0)
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->join('sizes', 'carts.size_id', 'sizes.id')
                    ->select(
                        'clothing.id as clothing_id',
                        'clothing.name as name',
                        'clothing.description as description',
                        'clothing.price as price',
                        'clothing.image as image',
                        'clothing.status as status',
                        'sizes.size as size',
                        'carts.quantity as quantity',
                        'carts.size_id as size_id'
                    )->get();
                $cloth_price = 0;

                foreach ($cartItems as $cart) {
                    $cloth_price += $cart->price * $cart->quantity;
                }
                $iva = $cloth_price * 0.13;
                $total_price = $cloth_price + $iva;

                $buy = new Buy();
                if ($request->hasFile('image')) {
                    $buy->image = $request->file('image')->store('uploads', 'public');
                }
                $buy->session_id =  $session_id;
                $buy->name =  $request->name;
                $buy->last_name =  $request->last_name;
                $buy->email =  $request->email;
                $buy->telephone =  $request->telephone;
                $buy->address =  $request->address;
                $buy->address_two =  $request->address_two;
                $buy->city =  $request->city;
                $buy->province =  $request->province;
                $buy->country =  $request->country;
                $buy->postal_code =  $request->postal_code;
                $buy->name =  $request->name;
                $buy->total_iva =  $iva;
                $buy->total_buy =  $total_price;
                $buy->delivered = 0;
                $buy->approved = 0;
                $buy->cancel_buy = 0;
                $buy->save();
                $buy_id = $buy->id;

                foreach ($cartItems as $cart) {
                    $buy_detail = new BuyDetail();
                    $buy_detail->buy_id = $buy_id;
                    $buy_detail->clothing_id = $cart->clothing_id;
                    $buy_detail->size_id = $cart->size_id;
                    $buy_detail->total = ($cart->price * $cart->quantity) + ($cart->price * 0.13);
                    $buy_detail->iva = $cart->price * 0.13;
                    $buy_detail->quantity = $cart->quantity;
                    $buy_detail->cancel_item = 0;
                    $buy_detail->save();
                    $stock = Stock::where('clothing_id', $cart->clothing_id)
                        ->where('size_id', $cart->size_id)
                        ->first();
                    Stock::where('clothing_id', $cart->clothing_id)
                        ->where('size_id', $cart->size_id)
                        ->update(['stock' => ($stock->stock - $cart->quantity)]);
                }

                Cart::where('session_id', $session_id)->where('sold', 0)->update(['sold' => 1]);
                DB::commit();
            }

            return redirect('/')->with(['status' => 'Se ha creado su compra con éxito, revise sus compras con su correo!', 'icon' => 'success']);
        } catch (Exception $th) {
            return redirect()->back()->with(['status' => 'Error! ' . $th, 'icon' => 'warning']);
            DB::rollBack();
        }
    }
}
