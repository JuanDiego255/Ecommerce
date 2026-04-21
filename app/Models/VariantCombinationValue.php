<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantCombinationValue extends Model
{
    protected $fillable = ['combination_id', 'attr_id', 'value_attr'];

    public function combination()
    {
        return $this->belongsTo(VariantCombination::class, 'combination_id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attr_id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'value_attr');
    }
}
