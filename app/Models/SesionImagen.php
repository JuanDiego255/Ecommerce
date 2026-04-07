<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionImagen extends Model
{
    use HasFactory;

    protected $table = 'sesion_imagenes';

    protected $fillable = [
        'sesion_id', 'paciente_id', 'tipo', 'path',
        'titulo', 'zona_corporal', 'descripcion', 'orden', 'es_favorita',
    ];

    protected $casts = [
        'es_favorita' => 'boolean',
    ];

    public function sesion()
    {
        return $this->belongsTo(SesionClinica::class, 'sesion_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function getUrlAttribute(): string
    {
        return route('file', $this->path);
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'antes'       => 'Antes',
            'durante'     => 'Durante',
            'despues'     => 'Después',
            'referencia'  => 'Referencia',
            default       => $this->tipo,
        };
    }

    public function getTipoBadgeAttribute(): string
    {
        return match($this->tipo) {
            'antes'      => 'pill-yellow',
            'despues'    => 'pill-green',
            'durante'    => 'pill-blue',
            'referencia' => 'pill-red',
            default      => 'pill-blue',
        };
    }
}
