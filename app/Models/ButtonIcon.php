<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ButtonIcon extends Model
{
    use HasFactory;

    protected $fillable = [
        'home',
        'categories',
        'cart',
        'shopping',
        'address',
        'user',
        'services',
        'products',
        'detail',
    ];
}
