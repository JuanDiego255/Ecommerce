<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Servicio extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
        'descripcion',
        'duration_minutes',
        'base_price_cents',
        'activo',
        'image'
    ];


    public function especialistas()
    {
        return $this->belongsToMany(Especialista::class)
            ->withPivot(['price_cents', 'duration_minutes', 'activo'])
            ->withTimestamps();
    }
}
