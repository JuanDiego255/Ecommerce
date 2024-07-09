<?php

namespace App\Http\Controllers\Frontend;

use Adrianorosa\GeoLocation\GeoLocation;
use App\Http\Controllers\Controller;
use App\Mail\Mail as MailMail;
use App\Mail\SampleMail;
use App\Models\AddressUser;
use App\Models\Advert;
use App\Models\AttributeValueBuy;
use App\Models\Buy;
use App\Models\BuyDetail;
use App\Models\Cart;
use App\Models\MetaTags;
use App\Models\Stock;
use App\Models\TenantInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class CheckOutController extends Controller
{
    //
    private $client;
    private $clientId;
    private $secret;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api-m.sandbox.paypal.com'
        ]);
        $this->clientId = env('PAYPAL_CLIENT_ID');
        $this->secret = env('PAYPAL_SECRET');
    }

    public function index()
    {
        $tenantinfo = TenantInfo::first();
        $userId = Auth::id();
        $user_info = null;
        if (Auth::check()) {
            $cartItems = Cart::where('carts.user_id', $userId)
                ->where('carts.session_id', null)
                ->where('carts.sold', 0)
                ->join('users', 'carts.user_id', 'users.id')
                ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                ->where('stocks.price', '!=', 0)
                ->leftJoin('stocks', function ($join) {
                    $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                        ->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')
                        ->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr');
                })
                ->join('clothing', 'carts.clothing_id', 'clothing.id')
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
                    'clothing.mayor_price as mayor_price',
                    'clothing.discount as discount',
                    'clothing.status as status',
                    'carts.quantity as quantity',
                    'carts.id as cart_id',
                    'attributes.name as name_attr',
                    'attribute_values.value as value',
                    'stocks.price as price',
                    'stocks.stock as stock',
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
                    'product_images.image'
                )
                ->get();
            $cloth_price = 0;

            foreach ($cartItems as $item) {
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
                $cloth_price += $precioConDescuento * $item->quantity;
            }
            $user_info = AddressUser::where('user_id', Auth::user()->id)
                ->where('status', 1)->first();
            $iva = $cloth_price * $tenantinfo->iva;
            $total_price = $cloth_price + $iva;
        } else {
            $session_id = session()->get('session_id');
            $cartItems = Cart::where('carts.session_id', $session_id)
                ->where('carts.user_id', null)
                ->where('carts.sold', 0)
                ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                ->where('stocks.price', '!=', 0)
                ->leftJoin('stocks', function ($join) {
                    $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                        ->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')
                        ->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr');
                })
                ->join('clothing', 'carts.clothing_id', 'clothing.id')
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
                    'clothing.mayor_price as mayor_price',
                    'clothing.discount as discount',
                    'clothing.status as status',
                    'carts.quantity as quantity',
                    'carts.id as cart_id',
                    'attributes.name as name_attr',
                    'attribute_values.value as value',
                    'stocks.price as price',
                    'stocks.stock as stock',
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
                    'product_images.image'
                )
                ->get();
            $cloth_price = 0;
            foreach ($cartItems as $item) {
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
                $cloth_price += $precioConDescuento * $item->quantity;
            }

            $iva = $cloth_price * $tenantinfo->iva;
            $total_price = $cloth_price + $iva;
        }

        $delivery = $tenantinfo->delivery;
        $tenant = $tenantinfo->tenant;

        /* $response = file_get_contents(env('URL_TIPO_CAMBIO_CR'));

        if ($response !== false) {
            $exchangeRates = json_decode($response, true);           
            $tipoCambio = $exchangeRates['venta'];   
            $tipoCambio = round($tipoCambio, 2);        
        } else {           
            $tipoCambio = 1;
        } */

        $paypal_amount = $total_price / 1;
        $paypal_amount = round($paypal_amount, 2);
        $tags = MetaTags::where('section', 'Checkout')->get();
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

        $advert = Advert::where('section', 'checkout')->latest()->first();

        return view('frontend.checkout', compact('cartItems', 'advert', 'tenant', 'delivery', 'iva', 'total_price', 'cloth_price', 'user_info', 'paypal_amount'));
    }

    public function payment(
        $name = null,
        $email = null,
        $telephone = null,
        $address = null,
        $address_two = null,
        $country = null,
        $city = null,
        $province = null,
        $postal_code = null
    ) {
        try {
            // Obtener la IP del usuario
            $userIp = request()->ip();

            // Utilizar una API de geolocalización para obtener la ubicación basada en la IP
            $details = GeoLocation::lookup($userIp);
            $countryCode = $details->getCountryCode();
            if ($countryCode != 'CR') {
                return redirect()->back()->with(['status' => 'No puede realizar una compra si se encuentra fuera de Costa Rica!', 'icon' => 'success']);
            }
            $tenantinfo = TenantInfo::first();
            $tenant = $tenantinfo->tenant;
            $request = request();
            DB::beginTransaction();
            if ($request->kind_of == "V") {
                $userId = Auth::id();
                if (Auth::check()) {
                    $cartItems = Cart::where('carts.user_id', $userId)
                        ->where('carts.session_id', null)
                        ->where('carts.sold', 0)
                        ->join('users', 'carts.user_id', 'users.id')
                        ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                        ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                        ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                        ->where('stocks.price', '!=', 0)
                        ->leftJoin('stocks', function ($join) {
                            $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                                ->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')
                                ->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr');
                        })
                        ->join('clothing', 'carts.clothing_id', 'clothing.id')
                        ->leftJoin('product_images', function ($join) {
                            $join->on('clothing.id', '=', 'product_images.clothing_id')
                                ->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images 
                                WHERE product_images.clothing_id = clothing.id
                            )');
                        })
                        ->select(
                            'clothing.id as clothing_id',
                            'clothing.name as name',
                            'clothing.code as code',
                            'clothing.manage_stock as manage_stock',
                            'clothing.casa as casa',
                            'clothing.description as description',
                            'clothing.mayor_price as mayor_price',
                            'clothing.discount as discount',
                            'clothing.status as status',
                            'carts.quantity as quantity',
                            'carts.id as cart_id',
                            'attributes.name as name_attr',
                            'attribute_values.value as value',
                            'stocks.price as price',
                            'stocks.stock as stock',
                            DB::raw('(
                                SELECT GROUP_CONCAT(CONCAT(attributes.id, "-", attribute_values.id) SEPARATOR ", ")
                                FROM attribute_value_cars
                                JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                                JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                                WHERE attribute_value_cars.cart_id = carts.id
                            ) as attributes_values'),
                            DB::raw('(
                                SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                                FROM attribute_value_cars
                                JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                                JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                                WHERE attribute_value_cars.cart_id = carts.id
                            ) as attributes_values_str'),
                            DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto

                        )
                        ->groupBy(
                            'clothing.id',
                            'clothing.name',
                            'clothing.casa',
                            'clothing.code',
                            'clothing.manage_stock',
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
                            'product_images.image'
                        )
                        ->get();
                    $cloth_price = 0;

                    foreach ($cartItems as $cart) {
                        $precio = $cart->price;
                        if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $cart->stock_price > 0) {
                            $precio = $cart->stock_price;
                        }
                        if (Auth::check() && Auth::user()->mayor == '1' && $cart->mayor_price > 0) {
                            $precio = $cart->mayor_price;
                        }
                        $descuentoPorcentaje = $cart->discount;
                        // Calcular el descuento
                        $descuento = ($precio * $descuentoPorcentaje) / 100;
                        // Calcular el precio con el descuento aplicado
                        $precioConDescuento = $precio - $descuento;
                        $cloth_price += $precioConDescuento * $cart->quantity;
                    }
                    $iva = $cloth_price * $tenantinfo->iva;
                    $total_price = $cloth_price + $iva;


                    $buy = new Buy();
                    if ($request !== null) {
                        if ($request->hasFile('image')) {
                            $buy->image = $request->file('image')->store('uploads', 'public');
                        }
                        if ($request->has('address')) {
                            $address = $request->address;
                        }
                        if ($request->has('address_two') && $tenant != "mandicr") {
                            $address_two = $request->address_two;
                        }
                        if ($request->has('telephone')) {
                            $telephone = $request->telephone;
                        }
                        if ($request->has('city')) {
                            $city = $request->city;
                        }
                        if ($request->has('province')) {
                            $province = $request->province;
                        }
                        if ($request->has('country')) {
                            $country = $request->country;
                        }
                        if ($request->has('postal_code')) {
                            $postal_code = $request->postal_code;
                        }
                    }

                    $buy->user_id =  Auth::user()->id;
                    $buy->address =  $address;
                    $buy->address_two =  $address_two;
                    $buy->city =  $city;
                    $buy->province =  $province;
                    $buy->country =  $country;
                    $buy->postal_code =  $postal_code;
                    $buy->total_iva =  $iva;
                    $buy->total_buy =  $total_price;
                    $buy->total_delivery =  $request->delivery;
                    $buy->delivered = 0;
                    $buy->approved = 0;
                    $buy->cancel_buy = 0;
                    $buy->kind_of_buy = $request->kind_of;
                    $buy->save();
                    $buy_id = $buy->id;

                    foreach ($cartItems as $cart) {
                        $precio = $cart->price;
                        if ($precio > 0) {
                            if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $cart->stock_price > 0) {
                                $precio = $cart->stock_price;
                            }
                            if (Auth::check() && Auth::user()->mayor == '1' && $cart->mayor_price > 0) {
                                $precio = $cart->mayor_price;
                            }
                            $descuentoPorcentaje = $cart->discount;
                            // Calcular el descuento
                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                            // Calcular el precio con el descuento aplicado
                            $precioConDescuento = $precio - $descuento;
                            $buy_detail = new BuyDetail();
                            $buy_detail->buy_id = $buy_id;
                            $buy_detail->clothing_id = $cart->clothing_id;
                            $buy_detail->total = ($precioConDescuento * $cart->quantity) + ($precioConDescuento * $tenantinfo->iva);
                            $buy_detail->iva = $precioConDescuento * $tenantinfo->iva;
                            $buy_detail->quantity = $cart->quantity;
                            $buy_detail->cancel_item = 0;
                            $buy_detail->save();
                            $buy_detail_id = $buy_detail->id;
                            $attributeValuePairs = explode(',', $cart->attributes_values);

                            foreach ($attributeValuePairs as $pair) {
                                list($attr_id, $value_attr) = explode('-', $pair);
                                $attr_val_buy = new AttributeValueBuy();
                                $attr_val_buy->buy_detail_id = $buy_detail_id;
                                $attr_val_buy->attr_id = $attr_id;
                                $attr_val_buy->value_attr = $value_attr;
                                $attr_val_buy->save();
                                if ($cart->manage_stock == 1) {
                                    $cart_quantity = $cart->quantity;
                                    $stock = Stock::where('clothing_id', $cart->clothing_id)
                                        ->where('attr_id', $attr_id)
                                        ->where('value_attr', $value_attr)
                                        ->first();
                                    if ($stock->price == 0) {
                                        $cart_quantity = 1;
                                    }
                                    Stock::where('clothing_id', $cart->clothing_id)
                                        ->where('attr_id', $attr_id)
                                        ->where('value_attr', $value_attr)
                                        ->update(['stock' => ($stock->stock - $cart_quantity)]);
                                }
                            }
                        }
                    }

                    Cart::where('user_id', Auth::user()->id)->where('sold', 0)->update(['sold' => 1]);
                    DB::commit();
                } else {
                    $session_id = session()->get('session_id');
                    $cartItems = Cart::where('carts.session_id', $session_id)
                        ->where('carts.user_id', null)
                        ->where('carts.sold', 0)
                        ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                        ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                        ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                        ->where('stocks.price', '!=', 0)
                        ->leftJoin('stocks', function ($join) {
                            $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                                ->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')
                                ->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr');
                        })
                        ->join('clothing', 'carts.clothing_id', 'clothing.id')
                        ->leftJoin('product_images', function ($join) {
                            $join->on('clothing.id', '=', 'product_images.clothing_id')
                                ->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images 
                                WHERE product_images.clothing_id = clothing.id
                            )');
                        })
                        ->select(
                            'clothing.id as clothing_id',
                            'clothing.name as name',
                            'clothing.casa as casa',
                            'clothing.code as code',
                            'clothing.manage_stock as manage_stock',
                            'clothing.description as description',
                            'clothing.mayor_price as mayor_price',
                            'clothing.discount as discount',
                            'clothing.status as status',
                            'carts.quantity as quantity',
                            'carts.id as cart_id',
                            'attributes.name as name_attr',
                            'attribute_values.value as value',
                            'stocks.price as price',
                            'stocks.stock as stock',
                            DB::raw('(
                                SELECT GROUP_CONCAT(CONCAT(attributes.id, "-", attribute_values.id) SEPARATOR ", ")
                                FROM attribute_value_cars
                                JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                                JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                                WHERE attribute_value_cars.cart_id = carts.id
                            ) as attributes_values'),
                            DB::raw('(
                                SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                                FROM attribute_value_cars
                                JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                                JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                                WHERE attribute_value_cars.cart_id = carts.id
                            ) as attributes_values_str'),
                            DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto

                        )
                        ->groupBy(
                            'clothing.id',
                            'clothing.name',
                            'clothing.casa',
                            'clothing.code',
                            'clothing.manage_stock',
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
                            'product_images.image'
                        )
                        ->get();
                    $cloth_price = 0;


                    foreach ($cartItems as $cart) {
                        $precio = $cart->price;
                        if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $cart->stock_price > 0) {
                            $precio = $cart->stock_price;
                        }
                        if (Auth::check() && Auth::user()->mayor == '1' && $cart->mayor_price > 0) {
                            $precio = $cart->mayor_price;
                        }
                        $descuentoPorcentaje = $cart->discount;
                        // Calcular el descuento
                        $descuento = ($precio * $descuentoPorcentaje) / 100;
                        // Calcular el precio con el descuento aplicado
                        $precioConDescuento = $precio - $descuento;
                        $cloth_price += $precioConDescuento * $cart->quantity;
                    }
                    $iva = $cloth_price * $tenantinfo->iva;
                    $total_price = $cloth_price + $iva;

                    $buy = new Buy();
                    if ($request !== null) {
                        if ($request->hasFile('image')) {
                            $buy->image = $request->file('image')->store('uploads', 'public');
                        }
                        if ($request->has('name')) {
                            $name = $request->name;
                        }
                        if ($request->has('email')) {
                            $email = $request->email;
                        }
                        if ($request->has('telephone')) {
                            $telephone = $request->telephone;
                        }
                        if ($request->has('address')) {
                            $address = $request->address;
                        }
                        if ($request->has('address_two') && $tenant != "mandicr") {
                            $address_two = $request->address_two;
                        }
                        if ($request->has('city')) {
                            $city = $request->city;
                        }
                        if ($request->has('province')) {
                            $province = $request->province;
                        }
                        if ($request->has('country')) {
                            $country = $request->country;
                        }
                        if ($request->has('postal_code')) {
                            $postal_code = $request->postal_code;
                        }
                    }
                    $buy->session_id =  $session_id;
                    $buy->name =  $name;
                    $buy->email =  $email;
                    $buy->telephone =  $telephone;
                    $buy->address =  $address;
                    $buy->address_two =  $address_two;
                    $buy->city =  $city;
                    $buy->province =  $province;
                    $buy->country =  $country;
                    $buy->postal_code =  $postal_code;
                    $buy->total_iva =  $iva;
                    $buy->total_buy =  $total_price;
                    $buy->total_delivery =  $request->delivery;
                    $buy->delivered = 0;
                    $buy->approved = 0;
                    $buy->cancel_buy = 0;
                    $buy->kind_of_buy = $request->kind_of;
                    $buy->save();
                    $buy_id = $buy->id;

                    foreach ($cartItems as $cart) {
                        $precio = $cart->price;
                        if ($precio > 0) {
                            if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $cart->stock_price > 0) {
                                $precio = $cart->stock_price;
                            }
                            if (Auth::check() && Auth::user()->mayor == '1' && $cart->mayor_price > 0) {
                                $precio = $cart->mayor_price;
                            }
                            $descuentoPorcentaje = $cart->discount;
                            // Calcular el descuento
                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                            // Calcular el precio con el descuento aplicado
                            $precioConDescuento = $precio - $descuento;
                            $buy_detail = new BuyDetail();
                            $buy_detail->buy_id = $buy_id;
                            $buy_detail->clothing_id = $cart->clothing_id;
                            $buy_detail->total = ($precioConDescuento * $cart->quantity) + ($precioConDescuento * $tenantinfo->iva);
                            $buy_detail->iva = $precioConDescuento * $tenantinfo->iva;
                            $buy_detail->quantity = $cart->quantity;
                            $buy_detail->cancel_item = 0;
                            $buy_detail->save();
                            $buy_detail_id = $buy_detail->id;
                            $attributeValuePairs = explode(',', $cart->attributes_values);

                            foreach ($attributeValuePairs as $pair) {
                                list($attr_id, $value_attr) = explode('-', $pair);
                                $attr_val_buy = new AttributeValueBuy();
                                $attr_val_buy->buy_detail_id = $buy_detail_id;
                                $attr_val_buy->attr_id = $attr_id;
                                $attr_val_buy->value_attr = $value_attr;
                                $attr_val_buy->save();
                                if ($cart->manage_stock == 1) {
                                    $stock = Stock::where('clothing_id', $cart->clothing_id)
                                        ->where('attr_id', $attr_id)
                                        ->where('value_attr', $value_attr)
                                        ->first();
                                    Stock::where('clothing_id', $cart->clothing_id)
                                        ->where('attr_id', $attr_id)
                                        ->where('value_attr', $value_attr)
                                        ->update(['stock' => ($stock->stock - $cart->quantity)]);
                                }
                            }
                        }
                    }

                    Cart::where('session_id', $session_id)->where('sold', 0)->update(['sold' => 1]);
                    DB::commit();
                }
            } else {
                $cartItems = Cart::where('user_id', null)
                    ->where('session_id', null)
                    ->where('sold', 0)
                    ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                    ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                    ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                    ->where('stocks.price', '!=', 0)
                    ->leftJoin('stocks', function ($join) {
                        $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                            ->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')
                            ->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr');
                    })
                    ->join('clothing', 'carts.clothing_id', 'clothing.id')
                    ->leftJoin('product_images', function ($join) {
                        $join->on('clothing.id', '=', 'product_images.clothing_id')
                            ->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images 
                                WHERE product_images.clothing_id = clothing.id
                            )');
                    })
                    ->select(
                        'clothing.id as clothing_id',
                        'clothing.name as name',
                        'clothing.casa as casa',
                        'clothing.code as code',
                        'clothing.manage_stock as manage_stock',
                        'clothing.description as description',
                        'clothing.mayor_price as mayor_price',
                        'clothing.discount as discount',
                        'clothing.status as status',
                        'carts.quantity as quantity',
                        'carts.id as cart_id',
                        'attributes.name as name_attr',
                        'attribute_values.value as value',
                        'stocks.price as price',
                        'stocks.stock as stock',
                        DB::raw('(
                            SELECT GROUP_CONCAT(CONCAT(attributes.id, "-", attribute_values.id) SEPARATOR ", ")
                            FROM attribute_value_cars
                            JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                            JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                            WHERE attribute_value_cars.cart_id = carts.id
                        ) as attributes_values'),
                        DB::raw('(
                            SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                            FROM attribute_value_cars
                            JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                            JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                            WHERE attribute_value_cars.cart_id = carts.id
                        ) as attributes_values_str'),
                        DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto

                    )
                    ->groupBy(
                        'clothing.id',
                        'clothing.name',
                        'clothing.casa',
                        'clothing.code',
                        'clothing.manage_stock',
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
                        'product_images.image'
                    )
                    ->get();
                $cloth_price = 0;
                foreach ($cartItems as $cart) {
                    $precio = $cart->price;
                    if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $cart->stock_price > 0) {
                        $precio = $cart->stock_price;
                    }
                    if (Auth::check() && Auth::user()->mayor == '1' && $cart->mayor_price > 0) {
                        $precio = $cart->mayor_price;
                    }
                    $descuentoPorcentaje = $cart->discount;
                    // Calcular el descuento
                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                    // Calcular el precio con el descuento aplicado
                    $precioConDescuento = $precio - $descuento;
                    $cloth_price += $precioConDescuento * $cart->quantity;
                }
                $iva = $cloth_price * $tenantinfo->iva;
                $total_price = $cloth_price + $iva;

                $buy = new Buy();
                $buy->user_id = null;
                $buy->total_iva =  $iva;
                $buy->total_buy =  $total_price;
                $buy->delivered = 1;
                $buy->approved = 1;
                $buy->cancel_buy = 0;
                $buy->kind_of_buy = $request->kind_of;
                $buy->detail = $request->detail;
                $buy->save();
                $buy_id = $buy->id;

                foreach ($cartItems as $cart) {
                    $precio = $cart->price;
                    if ($precio > 0) {
                        if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $cart->stock_price > 0) {
                            $precio = $cart->stock_price;
                        }
                        if (Auth::check() && Auth::user()->mayor == '1' && $cart->mayor_price > 0) {
                            $precio = $cart->mayor_price;
                        }
                        $descuentoPorcentaje = $cart->discount;
                        // Calcular el descuento
                        $descuento = ($precio * $descuentoPorcentaje) / 100;
                        // Calcular el precio con el descuento aplicado
                        $precioConDescuento = $precio - $descuento;
                        $buy_detail = new BuyDetail();
                        $buy_detail->buy_id = $buy_id;
                        $buy_detail->clothing_id = $cart->clothing_id;
                        $buy_detail->total = ($precioConDescuento * $cart->quantity) + ($precioConDescuento * $tenantinfo->iva);
                        $buy_detail->iva = $precioConDescuento * $tenantinfo->iva;
                        $buy_detail->quantity = $cart->quantity;
                        $buy_detail->cancel_item = 0;
                        $buy_detail->save();
                        $buy_detail_id = $buy_detail->id;
                        $attributeValuePairs = explode(',', $cart->attributes_values);

                        foreach ($attributeValuePairs as $pair) {
                            list($attr_id, $value_attr) = explode('-', $pair);
                            $attr_val_buy = new AttributeValueBuy();
                            $attr_val_buy->buy_detail_id = $buy_detail_id;
                            $attr_val_buy->attr_id = $attr_id;
                            $attr_val_buy->value_attr = $value_attr;
                            $attr_val_buy->save();
                            if ($cart->manage_stock == 1) {
                                $stock = Stock::where('clothing_id', $cart->clothing_id)
                                    ->where('attr_id', $attr_id)
                                    ->where('value_attr', $value_attr)
                                    ->first();
                                Stock::where('clothing_id', $cart->clothing_id)
                                    ->where('attr_id', $attr_id)
                                    ->where('value_attr', $value_attr)
                                    ->update(['stock' => ($stock->stock - $cart->quantity)]);
                            }
                        }
                    }
                }

                Cart::where('user_id', null)
                    ->where('session_id', null)
                    ->where('sold', 0)->update(['sold' => 1]);
                DB::commit();
                return redirect()->back()->with(['status' => 'Venta exitosa!', 'icon' => 'success']);
            }


            //dd($request->has('telephone'));
            if ($request->has('telephone')) {
                $this->sendEmail($cartItems, $total_price, $request->delivery);
                return redirect('/')->with(['status' => 'Compra exitosa!', 'icon' => 'success']);
            }

            return true;
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => $th->getMessage(), 'icon' => 'success']);
        }
    }
    public function getAccessToken()
    {
        try {
            $response = $this->client->request('POST', '/v1/oauth2/token', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ],
                'auth' => [
                    $this->clientId, $this->secret
                ]
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            return $data['access_token'] ?? null;
        } catch (\Exception $e) {
            // Manejo de errores
            return null;
        }
    }
    public function sendEmail($cartItems, $total_price, $delivery)
    {
        try {
            $tenantinfo = TenantInfo::first();
            $email = $tenantinfo->email;
            if ($email) {

                if ($delivery > 0) {
                    $total_price = $total_price + $delivery;
                }

                $details = [
                    'cartItems' => $cartItems,
                    'total_price' => $total_price,
                    'title' => 'Se realizó una venta por medio del sitio web - ' . $tenantinfo->title
                ];

                Mail::send('emails.sale', $details, function ($message) use ($details, $email) {
                    $message->to($email)
                        ->subject($details['title']);
                });
            }
            return true;
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }
    public function process($orderId)
    {
        try {
            $accessToken = $this->getAccessToken();

            if ($accessToken) {
                $response = $this->client->request('GET', "/v2/checkout/orders/$orderId", [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer $accessToken"
                    ],
                ]);

                $body = $response->getBody()->getContents();
                $data = json_decode($body, true); // Convierte el cuerpo de la respuesta a un arreglo asociativo

                if ($data['status'] === 'APPROVED') {
                    $name = $data['payer']['name']['given_name'] . ' ' . $data['payer']['name']['surname'];
                    $email = $data['payer']['email_address'];
                    $address = $data['purchase_units'][0]['shipping']['address']['address_line_1'];
                    $address_two = $data['purchase_units'][0]['shipping']['address']['address_line_2'];
                    $city = $data['purchase_units'][0]['shipping']['address']['admin_area_2'];
                    $province = $data['purchase_units'][0]['shipping']['address']['admin_area_1'];
                    $postal_code = $data['purchase_units'][0]['shipping']['address']['postal_code'];
                    return [
                        'success' => $this->payment($name, $email, '', $address, $address_two, 'Costa Rica', $city, $province, $postal_code),
                        'status' => 'Se realiz贸 la transacci贸n sin problemas.',
                        'icon' => 'success'
                    ];
                }
                return [
                    'success' => false,
                    'status' => 'Ocurri贸 un problema al realizar el pago.',
                    'icon' => 'error'
                ];
            } else {
                return 'No se pudo obtener el token de acceso';
            }
        } catch (\Exception $e) {
            // Manejo de errores
            return 'Error al procesar la solicitud: ' . $e->getMessage();
        }
    }
}
