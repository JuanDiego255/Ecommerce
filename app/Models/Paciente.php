<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'nombre', 'apellidos', 'cedula', 'fecha_nacimiento', 'sexo',
        'telefono', 'email', 'ocupacion', 'direccion', 'ciudad',
        'foto_perfil', 'grupo_sanguineo', 'fuente_referido',
        'notas_internas', 'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    public function expediente()
    {
        return $this->hasOne(ExpedienteClinico::class);
    }

    public function sesiones()
    {
        return $this->hasMany(SesionClinica::class)->orderByDesc('fecha_sesion');
    }

    public function imagenes()
    {
        return $this->hasMany(SesionImagen::class);
    }

    public function alertas()
    {
        return $this->hasMany(AlertaPaciente::class)->where('activa', true);
    }

    public function consentimientosFirmados()
    {
        return $this->hasMany(ConsentimientoFirmado::class);
    }

    // Accessors
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento
            ? $this->fecha_nacimiento->age
            : null;
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto_perfil
            ? route('file', $this->foto_perfil)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->nombre_completo) . '&background=5e72e4&color=fff&size=120';
    }
}
