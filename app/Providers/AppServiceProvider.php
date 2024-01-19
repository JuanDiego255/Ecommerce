<?php

namespace App\Providers;

use App\Models\Buy;
use App\Models\Cart;
use App\Models\Categories;
use App\Models\TenantCarousel;
use App\Models\TenantInfo;
use App\Models\TenantSocialNetwork;
use Illuminate\Support\Facades\Auth;
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
            $categories = Categories::get();
            $social_network = TenantSocialNetwork::get();
            $tenantcarousel = TenantCarousel::get();
            $instagram = null;
            $facebook = null;
            $twitter = null;
            foreach($social_network as $social){
                
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
                'tenantcarousel' => $tenantcarousel
            ]);
        });
    }
}
