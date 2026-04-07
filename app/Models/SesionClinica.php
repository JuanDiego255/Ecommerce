<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionClinica extends Model
{
    use HasFactory;

    protected $table = 'sesiones_clinicas';

    protected $fillable = [
        'paciente_id', 'plantilla_id', 'especialista_id',
        'titulo', 'fecha_sesion', 'hora_inicio', 'hora_fin', 'estado',
        'observaciones_pre', 'observaciones_post',
        'productos_usados', 'recomendaciones',
        'proxima_cita', 'notas_internas',
        'firma_paciente_path', 'firmado_en', 'created_by',
    ];

    protected $casts = [
        'fecha_sesion' => 'date',
        'proxima_cita' => 'date',
        'firmado_en' => 'datetime',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function plantilla()
    {
        return $this->belongsTo(FichaPlantilla::class, 'plantilla_id');
    }

    public function especialista()
    {
        return $this->belongsTo(Especialista::class, 'especialista_id');
    }

    public function respuestas()
    {
        return $this->hasMany(SesionRespuesta::class, 'sesion_id');
    }

    public function imagenes()
    {
        return $this->hasMany(SesionImagen::class, 'sesion_id')->orderBy('orden');
    }

    public function imagenesAntes()
    {
        return $this->hasMany(SesionImagen::class, 'sesion_id')->where('tipo', 'antes');
    }

    public function imagenesDespues()
    {
        return $this->hasMany(SesionImagen::class, 'sesion_id')->where('tipo', 'despues');
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'completada' => 'pill-green',
            'cancelada'  => 'pill-red',
            default      => 'pill-yellow',
        };
    }
}
