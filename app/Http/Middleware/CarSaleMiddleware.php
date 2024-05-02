<?php

namespace App\Http\Middleware;

use App\Models\TenantInfo;
use Closure;
use Illuminate\Http\Request;

class CarSaleMiddleware
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
        if ($tenant->kind_business == 1) {
            return $next($request);
        } else {
            abort(404);
        }
    }
}
