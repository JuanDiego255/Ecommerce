<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionRespuesta extends Model
{
    use HasFactory;

    protected $table = 'sesion_respuestas';

    protected $fillable = [
        'sesion_id', 'campo_key', 'campo_tipo', 'valor',
    ];

    public function sesion()
    {
        return $this->belongsTo(SesionClinica::class, 'sesion_id');
    }

    // Decode valor as array/object when it's JSON
    public function getValorDecodedAttribute(): mixed
    {
        if (in_array($this->campo_tipo, ['seleccion_multiple', 'escala'])) {
            return json_decode($this->valor, true);
        }
        return $this->valor;
    }
}
