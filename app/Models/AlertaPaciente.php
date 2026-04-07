<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertaPaciente extends Model
{
    use HasFactory;

    protected $table = 'alertas_paciente';

    protected $fillable = [
        'paciente_id', 'tipo', 'descripcion', 'nivel', 'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function getBadgeClassAttribute(): string
    {
        return match($this->nivel) {
            'danger'  => 'pill-red',
            'warning' => 'pill-yellow',
            default   => 'pill-blue',
        };
    }
}
