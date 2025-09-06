<?php

namespace App\Services;

use App\Models\Barbero;
use App\Models\Servicio;


class PricingService
{
    public function quoteFor(Barbero $barbero, array $servicioIds): array
    {
        $servicios = Servicio::whereIn('id', $servicioIds)->get();
        $total = 0;
        $detalle = [];
        foreach ($servicios as $srv) {
            $pivot = $barbero->servicios()->where('servicio_id', $srv->id)->first()?->pivot;
            $price = $pivot && $pivot->price_cents !== null ? $pivot->price_cents : $srv->base_price_cents;
            $dur = $pivot && $pivot->duration_minutes !== null ? $pivot->duration_minutes : $srv->duration_minutes;
            $total += $price;
            $detalle[] = ['servicio_id' => $srv->id, 'nombre' => $srv->nombre, 'price_cents' => $price, 'duration_minutes' => $dur];
        }
        return [$total, $detalle];
    }
}
