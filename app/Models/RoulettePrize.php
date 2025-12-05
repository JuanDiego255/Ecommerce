<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoulettePrize extends Model
{
    protected $fillable = [
        'label',
        'discount_percent',
        'weight',
        'active',
    ];
}
