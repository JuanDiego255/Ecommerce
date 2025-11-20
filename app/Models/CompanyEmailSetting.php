<?php

// app/Models/CompanyEmailSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

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

    // Si quieres encriptar password automáticamente:
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }

    public function getPasswordAttribute($value)
    {
        return decrypt($value);
    }
}
