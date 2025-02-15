<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy; // Asegúrate de usar el facade correcto según tu paquete
use App\Models\Tenant; // Modelo de tus tenants

class TenantMiddleware
{
    /**
     * Maneja la petición entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica si hay un tenant almacenado en la sesión
        if (session()->has('current_tenant')) {
            $tenantId = session('current_tenant');
            
            // Busca el tenant (puedes usar find o buscar por otro campo, según tu lógica)
            $tenant = Tenant::find($tenantId);
            
            if ($tenant) {
                // Inicializa el tenant (esto cambiará la conexión a la BD del tenant)
                Tenancy::initialize($tenant);
            }
        }
        
        return $next($request);
    }
}
