<?php

namespace App\Providers;

use App\Models\Buy;
use App\Models\Cart;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\Department;
use App\Models\PersonalUser;
use App\Models\Settings;
use App\Models\TenantCarousel;
use App\Models\TenantInfo;
use App\Models\TenantSocialNetwork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer('*', function ($view) {
            $view_name = str_replace('.', '_', $view->getName());
            $session_id = session()->get('session_id');
            if (Auth::check()) {
                $cartNumber = count(Cart::where('user_id', Auth::id())
                    ->where('session_id', null)
                    ->where('sold', 0)->get());
                $buys = count(Buy::where('user_id', Auth::id())
                    ->where('session_id', null)
                    ->get());
            } else {
                $cartNumber = count(Cart::where('session_id', $session_id)
                    ->where('user_id', null)
                    ->where('sold', 0)->get());
                $buys = count(Buy::where('user_id', null)
                    ->where('session_id', $session_id)
                    ->get());
            }
            $categories = Categories::where('departments.department', 'Default')
                ->join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.name as name',
                )
                ->orderBy('categories.name', 'asc')
                ->get();
            $social_network = TenantSocialNetwork::get();
            $tenantcarousel = TenantCarousel::get();
            $instagram = null;
            $facebook = null;
            $twitter = null;
            foreach ($social_network as $social) {

                if (stripos($social->social_network, 'Facebook') !== false) {
                    $facebook = $social->url;
                } elseif (stripos($social->social_network, 'Instagram') !== false) {
                    $instagram = $social->url;
                }
                if (stripos($social->social_network, 'Twitter') !== false) {
                    $twitter = $social->url;
                }
            }

            $tenantinfo = TenantInfo::first();
            $settings = Settings::first();
            $departments = Department::where('department', '!=', 'Default')->with('categories')
                ->orderBy('departments.department', 'asc')
                ->get();

            $clothings_offer = ClothingCategory::where('categories.name', 'Sale')
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'clothing.casa',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )
                ->orderBy('clothing.name', 'asc')
                ->take(8)
                ->get();

            $cart_items = Cache::remember('cart_items', 60, function () {
                if (Auth::check()) {
                    $userId = Auth::id();
                    $cart_items = Cart::where('carts.user_id', $userId)
                        ->where('carts.session_id', null)
                        ->where('carts.sold', 0)
                        ->join('users', 'carts.user_id', 'users.id')
                        ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                        ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                        ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                        ->where('stocks.price','!=',0)
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
                    // Resto del código para obtener los artículos del carrito para usuarios autenticados
                    return $cart_items;
                } else {
                    $session_id = session()->get('session_id');
                    $cart_items = Cart::where('carts.session_id', $session_id)
                        ->where('carts.user_id', null)
                        ->where('carts.sold', 0)
                        ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                        ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                        ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                        ->where('stocks.price','!=',0)
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
                       
                    return $cart_items;
                }
            });
            $cloth_price = 0;
            $you_save = 0;
            foreach ($cart_items as $item) {
                $precio = $item->price;
                if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 && $item->stock_price > 0) {
                    $precio = $item->stock_price;
                }
                if (
                    Auth::check() &&
                    Auth::user()->mayor == '1' &&
                    $item->mayor_price > 0
                ) {
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
            $profesional_info = null;
            if($tenantinfo->tenant != "main"){
                $profesional_info = PersonalUser::first();
            }
           

            $iva = $cloth_price * $tenantinfo->iva;
            $total_price = $cloth_price + $iva;
            view()->share([
                'view_name' => $view_name,
                'profesional_info' => $profesional_info,
                'cartNumber' => $cartNumber,
                'categories' => $categories,
                'buys' => $buys,
                'social_network' => $social_network,
                'tenantinfo' => $tenantinfo,
                'twitter' => $twitter,
                'instagram' => $instagram,
                'facebook' => $facebook,
                'tenantcarousel' => $tenantcarousel,
                'settings' => $settings,
                'clothings_offer' => $clothings_offer,
                'departments' => $departments,
                'cart_items' => $cart_items,
                'cloth_price' => $cloth_price,
                'iva' => $iva,
                'total_price' => $total_price,
                'you_save' => $you_save
            ]);
        });
    }
}
