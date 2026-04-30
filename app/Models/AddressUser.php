<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressUser extends Model
{
    use HasFactory;

    protected $table    = 'address_users';
    protected $fillable = [
        'user_id', 'address', 'address_two', 'city',
        'country', 'province', 'postal_code', 'status',
    ];
}
