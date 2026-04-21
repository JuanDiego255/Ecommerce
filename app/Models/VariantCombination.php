<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantCombination extends Model
{
    protected $fillable = ['clothing_id', 'price', 'stock', 'manage_stock'];

    public function values()
    {
        return $this->hasMany(VariantCombinationValue::class, 'combination_id');
    }

    public function clothing()
    {
        return $this->belongsTo(ClothingCategory::class, 'clothing_id');
    }
}
