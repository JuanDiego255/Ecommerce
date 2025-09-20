<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['event_id', 'category_id', 'nombre', 'apellidos', 'telefono', 'equipo', 'email', 'comprobante_pago', 'terminos', 'estado'];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function category()
    {
        return $this->belongsTo(EventCategory::class);
    }
}
