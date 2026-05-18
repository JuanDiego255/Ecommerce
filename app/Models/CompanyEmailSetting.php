<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Encryption\Encrypter;

class CompanyEmailSetting extends Model
{
    protected $fillable = [
        'tenant_id',
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
    ];

    private function emailEncrypter(): Encrypter
    {
        $raw = config('app.email_encryption_key');
        $key = str_starts_with($raw, 'base64:') ? base64_decode(substr($raw, 7)) : $raw;
        return new Encrypter($key, 'AES-256-CBC');
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = $this->emailEncrypter()->encrypt($value);
    }

    public function getPasswordAttribute($value): ?string
    {
        if (!$value) return null;
        try {
            return $this->emailEncrypter()->decrypt($value);
        } catch (DecryptException) {
            // Fallback: datos cifrados con APP_KEY antes de la migración a clave dedicada.
            try {
                return decrypt($value);
            } catch (DecryptException) {
                return null;
            }
        }
    }
}
