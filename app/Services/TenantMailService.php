<?php

// app/Services/TenantMailService.php

namespace App\Services;

use App\Models\CompanyEmailSetting;
use App\Models\TenantInfo;
use Illuminate\Support\Facades\Mail;

class TenantMailService
{
    /**
     * Devuelve un mailer configurado con las credenciales de BD del tenant actual.
     * Si no existe configuración en BD, usa el mailer por defecto del .env.
     * Tras llamar a este método, config('mail.from.address') y config('mail.from.name')
     * reflejan siempre el remitente correcto (BD o .env).
     */
    public function getMailer(): \Illuminate\Mail\Mailer
    {
        $tenantId = TenantInfo::first()->tenant ?? null;
        $settings = $tenantId
            ? CompanyEmailSetting::where('tenant_id', $tenantId)->first()
            : null;

        if ($settings) {
            config([
                'mail.mailers.dynamic' => [
                    'transport'  => $settings->mailer ?? 'smtp',
                    'host'       => $settings->host,
                    'port'       => $settings->port,
                    'encryption' => $settings->encryption,
                    'username'   => $settings->username,
                    'password'   => $settings->password,
                    'timeout'    => null,
                    'auth_mode'  => null,
                ],
                'mail.from.address' => $settings->from_address,
                'mail.from.name'    => $settings->from_name ?? config('app.name'),
            ]);
            return Mail::mailer('dynamic');
        }

        return Mail::mailer(config('mail.default', 'smtp'));
    }

    /**
     * Devuelve un mailer ya configurado para el tenant actual.
     * @deprecated Usa getMailer() que incluye fallback automático al .env.
     */
    public function forCurrentTenant()
    {
        $tenantId = TenantInfo::first()->tenant;
        return $this->forTenant($tenantId);
    }

    /**
     * Devuelve un mailer ya configurado para el tenant especificado.
     * @deprecated Usa getMailer() que incluye fallback automático al .env.
     */
    public function forTenant(string $tenantId)
    {
        $settings = CompanyEmailSetting::where('tenant_id', $tenantId)->firstOrFail();

        config([
            'mail.mailers.dynamic' => [
                'transport'  => $settings->mailer ?? 'smtp',
                'host'       => $settings->host,
                'port'       => $settings->port,
                'encryption' => $settings->encryption,
                'username'   => $settings->username,
                'password'   => $settings->password,
                'timeout'    => null,
                'auth_mode'  => null,
            ],
            'mail.from.address' => $settings->from_address,
            'mail.from.name'    => $settings->from_name ?? config('app.name'),
        ]);

        return Mail::mailer('dynamic');
    }
}
