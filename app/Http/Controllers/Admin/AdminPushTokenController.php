<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminDeviceToken;
use App\Models\TenantInfo; // lo estás usando en reservar(), así que asumo existe

class AdminPushTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token'    => ['required', 'string', 'max:500'],
            'platform' => ['nullable', 'string', 'max:50'],
        ]);

        // Obtenemos tenant actual; si lo manejas distinto, ajústalo
        $tenantId = optional(TenantInfo::first())->tenant;

        AdminDeviceToken::updateOrCreate(
            ['token' => $request->token], // si ya existe ese token en BD, lo actualizamos en la misma fila
            [
                'user_id'  => $request->user()->id ?? null,
                'tenant'   => $tenantId,
                'platform' => $request->input('platform', 'web'),
            ]
        );

        return response()->json(['ok' => true]);
    }
}
