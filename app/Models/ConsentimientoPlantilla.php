<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentimientoPlantilla extends Model
{
    use HasFactory;

    protected $table = 'consentimientos_plantillas';

    protected $fillable = ['nombre', 'contenido', 'tipo', 'activo', 'version'];

    protected $casts = ['activo' => 'boolean'];

    public function firmados()
    {
        return $this->hasMany(ConsentimientoFirmado::class, 'plantilla_id');
    }
}
