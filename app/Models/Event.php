<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['nombre', 'fecha_inscripcion', 'costo_crc', 'ubicacion', 'detalles', 'cuenta_sinpe','cuenta_iban', 'imagen_premios', 'activo'];
    public function categories()
    {
        return $this->hasMany(EventCategory::class);
    }
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
