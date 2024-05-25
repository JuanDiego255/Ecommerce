<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    public function clothing()
    {
        return $this->belongsTo(ClothingCategory::class, 'clothing_id');
    }

    public function value()
    {
        return $this->belongsTo(AttributeValue::class, 'value_attr');
    }
}
