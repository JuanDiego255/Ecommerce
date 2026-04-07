<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    use HasFactory;

    protected $table = 'protocolos';

    protected $fillable = [
        'nombre', 'descripcion', 'categoria', 'duracion_estimada_min',
        'nivel_dificultad', 'contraindicaciones',
        'materiales_necesarios', 'pasos', 'notas_post', 'activo', 'created_by',
    ];

    protected $casts = [
        'materiales_necesarios' => 'array',
        'pasos'                 => 'array',
        'activo'                => 'boolean',
    ];
}
