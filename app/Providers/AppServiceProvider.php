<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\ButtonIcon;
use App\Models\Buy;
use App\Models\Cart;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\Department;
use App\Models\Favorite;
use App\Models\PersonalUser;
use App\Models\Settings;
use App\Models\TenantCarousel;
use App\Models\TenantInfo;
use App\Models\TenantSocialNetwork;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Observers\GeneralObserver;
use Carbon\Carbon;

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
        /* ClothingCategory::observe(GeneralObserver::class);
        Categories::observe(GeneralObserver::class);
        Buy::observe(GeneralObserver::class);
        Blog::observe(GeneralObserver::class);
        User::observe(GeneralObserver::class); */
        Schema::defaultStringLength(191);
        view()->composer('*', function ($view) {
            $view_name = str_replace('.', '_', $view->getName());
            $session_id = session()->get('session_id');
            $favNumber = 0;
            if (Auth::check()) {
                $favNumber = count(Favorite::where('user_id', Auth::id())->get());
            }
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
                    'categories.black_friday as black_friday'
                )
                ->orderBy('categories.name', 'asc')
                ->get();
            $categories_all = Categories::join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.name as name',
                    'categories.black_friday as black_friday'
                )
                ->inRandomOrder()
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
            $icons = ButtonIcon::first();
            $departments = Department::where('department', '!=', 'Default')->with('categories')
                ->orderBy('departments.order', 'asc')
                ->get();

            $department_black_friday = Department::where('department', '!=', 'Default')
                ->where('black_friday', 1)
                ->with('categories')
                ->first();


            $category_black_friday = Categories::where('categories.black_friday', 1)
                ->join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.image as image',
                    'categories.name as name',
                    'categories.black_friday as black_friday'
                )
                ->first();

            $clothings_offer = ClothingCategory::where('categories.name', 'Sale')
                ->join('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->join('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
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
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
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
                        ->leftJoin('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                        ->leftJoin('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                        ->leftJoin('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                        ->leftJoin('stocks', function ($join) {
                            $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                                ->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')
                                ->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr')
                                ->where('stocks.price', '!=', 0);
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
                            'clothing.name',
                            'clothing.casa',
                            'clothing.price',
                            'clothing.stock',
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
                        ->leftJoin('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                        ->leftJoin('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                        ->leftJoin('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                        ->leftJoin('stocks', function ($join) {
                            $join->on('carts.clothing_id', '=', 'stocks.clothing_id')
                                ->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')
                                ->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr')
                                ->where('stocks.price', '!=', 0);
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
                            'clothing.name',
                            'clothing.casa',
                            'clothing.price',
                            'clothing.stock',
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
            if ($tenantinfo->tenant != "main") {
                $profesional_info = PersonalUser::first();
            }
            $profesionals = PersonalUser::get();


            $iva = $cloth_price * $tenantinfo->iva;
            $total_price = $cloth_price + $iva;
            $clothing_favs = null;
            $fechaCostaRica = Carbon::now('America/Costa_Rica')->toDateString();
            if (Auth::check()) {
                $clothing_favs = Favorite::where('user_id', Auth::user()->id)->get();
            }
            view()->share([
                'view_name' => $view_name,
                'fechaCostaRica' => $fechaCostaRica,
                'clothing_favs' => $clothing_favs,
                'profesionals' => $profesionals,
                'profesional_info' => $profesional_info,
                'cartNumber' => $cartNumber,
                'favNumber' => $favNumber,
                'categories' => $categories,
                'categories_all' => $categories_all,
                'buys' => $buys,
                'social_network' => $social_network,
                'tenantinfo' => $tenantinfo,
                'twitter' => $twitter,
                'instagram' => $instagram,
                'facebook' => $facebook,
                'tenantcarousel' => $tenantcarousel,
                'settings' => $settings,
                'icon' => $icons,
                'clothings_offer' => $clothings_offer,
                'departments' => $departments,
                'cart_items' => $cart_items,
                'cloth_price' => $cloth_price,
                'iva' => $iva,
                'total_price' => $total_price,
                'you_save' => $you_save,
                'department_black_friday' => $department_black_friday,
                'category_black_friday' => $category_black_friday
            ]);
        });
    }
}
