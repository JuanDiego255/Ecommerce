<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
