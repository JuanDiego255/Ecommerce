<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothingCategory extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'clothing';
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'price',
        'image',
        'stock',
        'trending',
        'status'
    ];
}
