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
        'notas_internas', 'activo', 'portal_token', 'portal_token_expires_at',
    ];

    protected $casts = [
        'fecha_nacimiento'        => 'date',
        'activo'                  => 'boolean',
        'portal_token_expires_at' => 'datetime',
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

    public function hasActivePortalToken(): bool
    {
        return $this->portal_token !== null
            && ($this->portal_token_expires_at === null || $this->portal_token_expires_at->isFuture());
    }

    public function generatePortalToken(int $days = 30): string
    {
        $token = bin2hex(random_bytes(32)); // 64 hex chars
        $this->update([
            'portal_token'            => $token,
            'portal_token_expires_at' => now()->addDays($days),
        ]);
        return $token;
    }

    public function revokePortalToken(): void
    {
        $this->update(['portal_token' => null, 'portal_token_expires_at' => null]);
    }
}
