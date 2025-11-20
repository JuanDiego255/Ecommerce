<?php

// app/Http/Controllers/Admin/ClientController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyEmailSetting;
use App\Models\TenantInfo;
use Illuminate\Http\Request;

class EmailSettingController extends Controller
{
    public function index()
    {
        $tenantId = TenantInfo::first()->tenant;
        $setting_email = CompanyEmailSetting::where('tenant_id', $tenantId)->first();
        return view('admin.settings.email', compact('setting_email'));
    }

    public function update(Request $request)
    {
        $tenantId = TenantInfo::first()->tenant;

        $data = $request->validate([
            'username'     => ['required', 'string', 'max:255'],
            'from_address' => ['required', 'string', 'max:255'],
            'from_name'    => ['required', 'string', 'max:255'],
            'password'     => ['required', 'string', 'max:50'],
        ]);

        // Agregamos los valores fijos
        $data = array_merge($data, [
            'tenant_id'  => $tenantId,
            'mailer'     => 'smtp',
            'host'       => 'smtp.gmail.com',
            'port'       => 587,
            'encryption' => 'tls',
        ]);

        // Crea o actualiza según exista o no el registro
        CompanyEmailSetting::updateOrCreate(
            ['tenant_id' => $tenantId], // Buscar por tenant
            $data        // Actualizar o crear con estos datos
        );

        return back()->with('ok', 'Configuración guardada correctamente.');
    }
}
