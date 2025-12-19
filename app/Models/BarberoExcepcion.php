<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberoExcepcion extends Model
{
    protected $table = 'barbero_excepciones';

    protected $fillable = ['barbero_id', 'date', 'date_to', 'motivo'];

    protected $casts = [
        'date' => 'date',
        'date_to'   => 'date',
    ];

    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }

    // Scope: rangos que cubren una fecha específica
    public function scopeCubreFecha($query, $date)
    {
        return $query->whereDate('date', '<=', $date)
            ->whereDate('date_to', '>=', $date);
    }
}
