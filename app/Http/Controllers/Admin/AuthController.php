<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    //
    public function redirectFacebook()
    {
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (\Throwable $th) {
        }
    }

    public function callbackFacebook()
    {
        try {
            $user_facebook = Socialite::driver('facebook')->user();
            $name = $user_facebook->user['given_name'];
            $last_name = $user_facebook->user['family_name'];
            $user = User::updateOrCreate([
                'auth_id' => $user_facebook->id,
            ], [
                'name' => $name,
                'last_name' => $last_name,
                'email' => $user_facebook->email,
                'role_as' => 0,
            ]);

            Auth::login($user);
            if (Auth::user()->role_as == '1') {
                return redirect('categories')->with(['status' => 'Hola ' . Auth::user()->name . ' ' . Auth::user()->last_name, 'icon' => 'success']);
            } elseif (Auth::user()->role_as == '0') {
                return redirect('/')->with(['status' => 'Hola ' . Auth::user()->name . ' ' . Auth::user()->last_name, 'icon' => 'success']);
            }
        } catch (\Throwable $th) {
        }
    }

    public function redirectGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $th) {
        }
    }

    public function callbackGoogle()
    {
        $user_google = Socialite::driver('google')->user();
        $name = $user_google->user['given_name'];
        $last_name = $user_google->user['family_name'];
        $user = User::updateOrCreate([
            'auth_id' => $user_google->id,
        ], [
            'name' => $name,
            'last_name' => $last_name,
            'email' => $user_google->email,
            'role_as' => 0,
        ]);

        Auth::login($user);
        if (Auth::user()->role_as == '1') {
            return redirect('categories')->with(['status' => 'Hola ' . Auth::user()->name . ' ' . Auth::user()->last_name, 'icon' => 'success']);
        } elseif (Auth::user()->role_as == '0') {
            return redirect('/')->with(['status' => 'Hola ' . Auth::user()->name . ' ' . Auth::user()->last_name, 'icon' => 'success']);
        }
    }
}
