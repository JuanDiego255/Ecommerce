<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaPlantilla extends Model
{
    use HasFactory;

    protected $table = 'ficha_plantillas';

    protected $fillable = [
        'nombre', 'descripcion', 'categoria', 'icono', 'color_etiqueta',
        'campos', 'version', 'activa', 'es_sistema', 'created_by',
    ];

    protected $casts = [
        'campos'     => 'array',
        'activa'     => 'boolean',
        'es_sistema' => 'boolean',
    ];

    public function sesiones()
    {
        return $this->hasMany(SesionClinica::class, 'plantilla_id');
    }

    // Returns all fields flat (for rendering / filling sessions)
    public function getCamposPlanoAttribute(): array
    {
        if (!$this->campos || !isset($this->campos['secciones'])) return [];
        $campos = [];
        foreach ($this->campos['secciones'] as $seccion) {
            foreach ($seccion['campos'] ?? [] as $campo) {
                $campos[$campo['key']] = $campo;
            }
        }
        return $campos;
    }
}
