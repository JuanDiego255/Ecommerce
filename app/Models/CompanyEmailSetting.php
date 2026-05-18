<?php

// app/Models/CompanyEmailSetting.php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;

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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }

    public function getPasswordAttribute($value)
    {
        if (!$value) return null;
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return null;
        }
    }
}
