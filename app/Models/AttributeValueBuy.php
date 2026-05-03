<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValueBuy extends Model
{
    use HasFactory;

    protected $fillable = ['buy_detail_id', 'attr_id', 'value_attr'];
}
