<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'address_user_id', 'session_id', 'name', 'email',
        'telephone', 'address', 'address_two', 'city', 'province',
        'country', 'postal_code', 'total_iva', 'total_buy', 'total_delivery',
        'delivered', 'approved', 'ready_to_give', 'kind_of_buy', 'detail',
        'cancel_buy', 'image', 'cart_id', 'guide_number', 'apartado', 'monto_apartado',
    ];
}
