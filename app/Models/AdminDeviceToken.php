<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDeviceToken extends Model
{
    protected $fillable = [
        'user_id',
        'tenant',
        'token',
        'platform',
    ];
}
