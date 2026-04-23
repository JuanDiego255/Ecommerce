<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialista extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'salario_base',
        'monto_por_servicio',
        'aplica_calc',
        'aplica_porc_tarjeta',
        'aplica_porc_113',
        'aplica_porc_prod',
        'set_campo_esp',
    ];

    protected $casts = [
        'salario_base'       => 'decimal:2',
        'monto_por_servicio' => 'decimal:2',
        'aplica_calc'        => 'boolean',
        'aplica_porc_tarjeta'=> 'boolean',
        'aplica_porc_113'    => 'boolean',
        'aplica_porc_prod'   => 'boolean',
        'set_campo_esp'      => 'boolean',
    ];

    public function servicios()
    {
        return $this->hasMany(PivotServiciosEspecialista::class, 'especialista_id');
    }

    public function ventas()
    {
        return $this->hasMany(VentaEspecialista::class, 'especialista_id');
    }
}
