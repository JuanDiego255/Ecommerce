<?php

namespace App\Http\Middleware;

use App\Models\TenantInfo;
use Closure;
use Illuminate\Http\Request;

class LicenseMiddleware
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
        $tenant = TenantInfo::first();
        if ($tenant->license == 1) {
            return $next($request);
        } else {
            return redirect('/vencido')->with(['status' => 'Sitio deshabilitado, comunícate con el admin del servicio para reactivarlo', 'icon' => 'warning']);
        }
    }
}
