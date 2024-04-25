<?php

namespace App\Providers;

use App\Models\Buy;
use App\Models\Cart;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\Department;
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
                ->orderBy('categories.name','asc')
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
            ->orderBy('departments.department','asc')
            ->get();

            $clothings_offer = ClothingCategory::where('categories.name', 'Sale')
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->join('sizes', 'stocks.size_id', 'sizes.id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )
                ->orderBy('clothing.name','asc')
                ->take(8)
                ->get();

            view()->share([
                'view_name' => $view_name,
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
                'departments' => $departments
            ]);
        });
    }
}
