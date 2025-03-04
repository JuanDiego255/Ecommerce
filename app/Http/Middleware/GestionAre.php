<?php

namespace App\Http\Middleware;

use App\Models\TenantInfo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestionAre
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantinfo = TenantInfo::first();

        if ($tenantinfo->tenant == "gestionarecr") {
            if(!Auth::check()){
                return redirect('/login')->with(['status' => 'Esta sección solo permite gestionar las ventas de Clínica Are -  Debes Iniciar Sesión', 'icon' => 'warning']);
            }
            if (Auth::user()->role_as == '1') {
                return redirect('/categories')->with(['status' => 'Bienvenido', 'icon' => 'success']);
            } else {
                return redirect('/login')->with(['status' => 'Acceso denegado, no eres administrador', 'icon' => 'warning']);
            }
        } else {
            return $next($request);
        }
    }
}
