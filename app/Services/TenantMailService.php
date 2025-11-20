<?php

// app/Services/TenantMailService.php

namespace App\Services;

use App\Models\CompanyEmailSetting;
use App\Models\TenantInfo;
use Illuminate\Support\Facades\Mail;

class TenantMailService
{
    /**
     * Devuelve un mailer ya configurado para el tenant actual.
     */
    public function forCurrentTenant()
    {
        $tenantId = TenantInfo::first()->tenant;

        return $this->forTenant($tenantId);
    }

    /**
     * Devuelve un mailer ya configurado para el tenant especificado.
     */
    public function forTenant(string $tenantId)
    {
        $settings = CompanyEmailSetting::where('tenant_id', $tenantId)->firstOrFail();

        // Configuramos el mailer dinámico
        config([
            'mail.mailers.dynamic' => [
                'transport'  => $settings->mailer ?? 'smtp',
                'host'       => $settings->host,
                'port'       => $settings->port,
                'encryption' => $settings->encryption,
                'username'   => $settings->username,
                'password'   => $settings->password, // ya desencriptado por el accessor
                'timeout'    => null,
                'auth_mode'  => null,
            ],
            'mail.from.address' => $settings->from_address,
            'mail.from.name'    => $settings->from_name ?? config('app.name'),
        ]);

        return Mail::mailer('dynamic');
    }
}
