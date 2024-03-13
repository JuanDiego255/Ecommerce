<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AddressUser;
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
                    'clothing.discount as discount',
                    'clothing.price as price',
                    'clothing.status as status',
                    'sizes.size as size',
                    'carts.quantity as quantity'

                )->get();
            $cloth_price = 0;
            foreach ($cartItems as $item) {
                $precio = $item->price;
                $descuentoPorcentaje = $item->discount;
                // Calcular el descuento
                $descuento = ($precio * $descuentoPorcentaje) / 100;
                // Calcular el precio con el descuento aplicado
                $precioConDescuento = $precio - $descuento;
                $cloth_price += $precioConDescuento * $item->quantity;
            }
            $user_info = AddressUser::where('user_id', Auth::user()->id)
                ->where('status', 1)->first();
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
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.status as status',
                    'sizes.size as size',
                    'carts.quantity as quantity'

                )->get();
            $cloth_price = 0;
            foreach ($cartItems as $item) {
                $precio = $item->price;
                $descuentoPorcentaje = $item->discount;
                // Calcular el descuento
                $descuento = ($precio * $descuentoPorcentaje) / 100;
                // Calcular el precio con el descuento aplicado
                $precioConDescuento = $precio - $descuento;
                $cloth_price += $precioConDescuento * $item->quantity;
            }

            $iva = $cloth_price * 0.13;
            $total_price = $cloth_price + $iva;
        }

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
            SEOMeta::setTitle($tag->title . " - " .$tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        return view('frontend.checkout', compact('cartItems', 'iva', 'total_price', 'cloth_price', 'user_info', 'paypal_amount'));
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
            $request = request();
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
                        'clothing.discount as discount',
                        'clothing.status as status',
                        'sizes.size as size',
                        'carts.quantity as quantity',
                        'carts.size_id as size_id'
                    )->get();
                $cloth_price = 0;

                foreach ($cartItems as $cart) {
                    $precio = $cart->price;
                    $descuentoPorcentaje = $cart->discount;
                    // Calcular el descuento
                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                    // Calcular el precio con el descuento aplicado
                    $precioConDescuento = $precio - $descuento;
                    $cloth_price += $precioConDescuento * $cart->quantity;
                }
                $iva = $cloth_price * 0.13;
                $total_price = $cloth_price + $iva;

                $buy = new Buy();
                if ($request !== null) {
                    if ($request->hasFile('image')) {
                        $buy->image = $request->file('image')->store('uploads', 'public');
                    }
                    if ($request->has('address')) {
                        $address = $request->address;
                    }
                    if ($request->has('address_two')) {
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
                $buy->save();
                $buy_id = $buy->id;

                foreach ($cartItems as $cart) {
                    $precio = $cart->price;
                    $descuentoPorcentaje = $cart->discount;
                    // Calcular el descuento
                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                    // Calcular el precio con el descuento aplicado
                    $precioConDescuento = $precio - $descuento;
                    $buy_detail = new BuyDetail();
                    $buy_detail->buy_id = $buy_id;
                    $buy_detail->clothing_id = $cart->clothing_id;
                    $buy_detail->size_id = $cart->size_id;
                    $buy_detail->total = ($precioConDescuento * $cart->quantity) + ($precioConDescuento * 0.13);
                    $buy_detail->iva = $precioConDescuento * 0.13;
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
                        'clothing.discount as discount',
                        'clothing.price as price',
                        'clothing.status as status',
                        'sizes.size as size',
                        'carts.quantity as quantity',
                        'carts.size_id as size_id'
                    )->get();
                $cloth_price = 0;

                foreach ($cartItems as $cart) {
                    $precio = $cart->price;
                    $descuentoPorcentaje = $cart->discount;
                    // Calcular el descuento
                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                    // Calcular el precio con el descuento aplicado
                    $precioConDescuento = $precio - $descuento;
                    $cloth_price += $precioConDescuento * $cart->quantity;
                }
                $iva = $cloth_price * 0.13;
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
                    if ($request->has('address_two')) {
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
                $buy->save();
                $buy_id = $buy->id;

                foreach ($cartItems as $cart) {
                    $precio = $cart->price;
                    $descuentoPorcentaje = $cart->discount;
                    // Calcular el descuento
                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                    // Calcular el precio con el descuento aplicado
                    $precioConDescuento = $precio - $descuento;
                    $buy_detail = new BuyDetail();
                    $buy_detail->buy_id = $buy_id;
                    $buy_detail->clothing_id = $cart->clothing_id;
                    $buy_detail->size_id = $cart->size_id;
                    $buy_detail->total = ($precioConDescuento * $cart->quantity) + ($precioConDescuento * 0.13);
                    $buy_detail->iva = $precioConDescuento * 0.13;
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
            if ($request->has('telephone')) {
                return redirect('/')->with(['status' => 'Se ha realizado la compra con 茅xito.', 'icon' => 'success']);
            }
            return true;
        } catch (Exception $th) {
            return false;
            DB::rollBack();
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
