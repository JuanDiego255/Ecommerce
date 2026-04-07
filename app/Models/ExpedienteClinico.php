<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteClinico extends Model
{
    use HasFactory;

    protected $table = 'expedientes_clinicos';

    protected $fillable = [
        'paciente_id', 'numero_expediente', 'fecha_apertura', 'ultima_visita',
        'alergias', 'medicamentos_actuales', 'condiciones_medicas',
        'antecedentes_familiares', 'antecedentes_esteticos',
        'embarazo', 'lactancia', 'diabetes', 'hipertension', 'epilepsia',
        'problemas_coagulacion', 'piel_sensible', 'queloides', 'rosacea',
        'fuma', 'consume_alcohol',
        'observaciones_generales', 'consentimiento_general_firmado', 'consentimiento_fecha',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'ultima_visita' => 'date',
        'consentimiento_fecha' => 'datetime',
        'embarazo' => 'boolean',
        'lactancia' => 'boolean',
        'diabetes' => 'boolean',
        'hipertension' => 'boolean',
        'epilepsia' => 'boolean',
        'problemas_coagulacion' => 'boolean',
        'piel_sensible' => 'boolean',
        'queloides' => 'boolean',
        'rosacea' => 'boolean',
        'fuma' => 'boolean',
        'consume_alcohol' => 'boolean',
        'consentimiento_general_firmado' => 'boolean',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Generate unique expedition number
    public static function generarNumero(): string
    {
        $year = date('Y');
        $ultimo = static::whereYear('created_at', $year)->count();
        return 'EXP-' . $year . '-' . str_pad($ultimo + 1, 4, '0', STR_PAD_LEFT);
    }

    // Conditions summary for alert badges
    public function getCondicionesActivasAttribute(): array
    {
        $conditions = [];
        if ($this->embarazo)              $conditions[] = ['label' => 'Embarazada', 'level' => 'danger'];
        if ($this->lactancia)             $conditions[] = ['label' => 'Lactancia', 'level' => 'warning'];
        if ($this->diabetes)              $conditions[] = ['label' => 'Diabetes', 'level' => 'warning'];
        if ($this->hipertension)          $conditions[] = ['label' => 'Hipertensión', 'level' => 'warning'];
        if ($this->epilepsia)             $conditions[] = ['label' => 'Epilepsia', 'level' => 'danger'];
        if ($this->problemas_coagulacion) $conditions[] = ['label' => 'Coagulación', 'level' => 'danger'];
        if ($this->queloides)             $conditions[] = ['label' => 'Queloides', 'level' => 'warning'];
        if ($this->rosacea)               $conditions[] = ['label' => 'Rosácea', 'level' => 'info'];
        return $conditions;
    }
}
