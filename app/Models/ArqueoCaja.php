<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ArqueoCaja extends Model
{
    protected $table = 'arqueo_cajas';

    public function scopeCajaAbiertaHoy(Builder $query, $fecha)
    {
        $hoy = Carbon::today('America/Costa_Rica');
        return $query->whereDate('fecha_ini', $hoy)
                     ->where('estado', 1);
    }
}
