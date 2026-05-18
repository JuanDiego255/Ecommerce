<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEmailDomain implements Rule
{
    private string $reason = 'formato';

    public function passes($attribute, $value): bool
    {
        // ── 1. Validación sintáctica ──────────────────────────────────────────
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->reason = 'formato';
            return false;
        }

        if (strlen($value) > 254) {
            $this->reason = 'formato';
            return false;
        }

        $atPos = strrpos($value, '@');
        $local  = substr($value, 0, $atPos);
        $domain = substr($value, $atPos + 1);

        if (strlen($local) > 64) {
            $this->reason = 'formato';
            return false;
        }

        // ── 2. Verificación DNS: solo registros MX ───────────────────────────
        // No usamos fallback de registro A ni gethostbyname() porque el hosting
        // hace DNS wildcarding: resuelve cualquier dominio a una IP propia,
        // lo que haría pasar dominios inexistentes.
        if ($this->hasMx($domain)) {
            return true;
        }

        $this->reason = 'dominio';
        return false;
    }

    public function message(): string
    {
        return $this->reason === 'dominio'
            ? 'El dominio del correo no existe o no puede recibir mensajes. Verificá que esté escrito correctamente (ej: usuario@gmail.com).'
            : 'El formato del correo electrónico no es válido.';
    }

    private function hasMx(string $domain): bool
    {
        try {
            $records = @dns_get_record($domain, DNS_MX);
            if (is_array($records) && count($records) > 0) {
                return true;
            }
            return (bool) @checkdnsrr($domain, 'MX');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
