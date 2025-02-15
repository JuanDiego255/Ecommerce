<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tenancy\Facades\Tenancy;

class SetTenantDatabase
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si la ruta contiene el prefijo '/aclimate'
        if (strpos($request->path(), 'aclimate') === 0) {
            // Suponiendo que tienes un tenant con nombre 'aclimate'
            $tenant = \App\Models\Tenant::where('id', 'aclimate')->first();

            if ($tenant) {
                // Inicializar la base de datos para este tenant
                tenancy()->initialize($tenant);
            }
        }

        return $next($request);
    }
}
