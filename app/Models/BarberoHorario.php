<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberoHorario extends Model
{
    protected $table = 'barbero_horarios';

    protected $fillable = ['barbero_id', 'dias', 'hora_inicio', 'hora_fin'];

    protected $casts = [
        'dias' => 'array',
    ];

    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }
}
