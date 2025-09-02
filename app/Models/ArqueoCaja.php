<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ArqueoCaja extends Model
{
    protected $table = 'arqueo_cajas';

    public function scopeCajaAbiertaHoy(Builder $query, $fecha = null)
    {
        $tz = 'America/Costa_Rica';

        // Normaliza la fecha de consulta (string/Carbon) a Y-m-d en la TZ de CR
        $fechaConsulta = $fecha
            ? Carbon::parse($fecha, $tz)->toDateString()
            : Carbon::today($tz)->toDateString();

        $esHoy = $fechaConsulta === Carbon::today($tz)->toDateString();

        $query->whereDate('fecha_ini', $fechaConsulta);

        if ($esHoy) {
            // Hoy: solo cajas abiertas
            $query->where('estado', 1);
        } else {
            // Otra fecha: se admite cerrada (0) o abierta (1)
            $query->whereIn('estado', [0, 1]);
            // Si prefieres "cualquier estado" en fechas pasadas, elimina la línea anterior.
        }

        return $query;
    }
}
