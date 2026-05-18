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

        // ── 2. Verificación DNS: registros MX ────────────────────────────────
        if ($this->hasMx($domain)) {
            return true;
        }

        // ── 3. Fallback DNS: registro A ──────────────────────────────────────
        if ($this->hasA($domain)) {
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
            // dns_get_record es más fiable que checkdnsrr en hosting compartido
            $records = @dns_get_record($domain, DNS_MX);
            if (is_array($records) && count($records) > 0) {
                return true;
            }
            // Segundo intento con checkdnsrr
            return (bool) @checkdnsrr($domain, 'MX');
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function hasA(string $domain): bool
    {
        try {
            $records = @dns_get_record($domain, DNS_A);
            if (is_array($records) && count($records) > 0) {
                return true;
            }
            if (@checkdnsrr($domain, 'A')) {
                return true;
            }
            // Último recurso: resolución de hostname
            $resolved = gethostbyname($domain);
            return $resolved !== $domain;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
