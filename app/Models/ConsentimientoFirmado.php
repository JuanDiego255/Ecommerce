<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentimientoFirmado extends Model
{
    use HasFactory;

    protected $table = 'consentimientos_firmados';

    protected $fillable = [
        'paciente_id', 'plantilla_id', 'sesion_id',
        'contenido_al_firmar', 'firma_path', 'pdf_path',
        'ip_firma', 'firmado_en',
    ];

    protected $casts = ['firmado_en' => 'datetime'];

    public function paciente() { return $this->belongsTo(Paciente::class); }
    public function plantilla() { return $this->belongsTo(ConsentimientoPlantilla::class, 'plantilla_id'); }
    public function sesion()   { return $this->belongsTo(SesionClinica::class, 'sesion_id'); }
}
