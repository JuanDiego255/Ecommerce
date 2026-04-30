<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberoDescanso extends Model
{
    protected $table = 'barbero_descansos';

    protected $fillable = ['barbero_id', 'dias', 'hora_inicio', 'hora_fin', 'motivo'];

    protected $casts = [
        'dias' => 'array',
    ];

    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }
}
