<?php

namespace App\Http\Middleware;

use App\Models\TenantInfo;
use Closure;
use Illuminate\Http\Request;

class EcdAccess
{
    public function handle(Request $request, Closure $next)
    {
        $tenantinfo = TenantInfo::first();

        if ($tenantinfo && $tenantinfo->is_ecd) {
            return $next($request);
        }

        abort(403, 'El módulo de Expediente Clínico Digital no está habilitado para este negocio.');
    }
}
