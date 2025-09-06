<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberoBloque extends Model
{
    protected $fillable = ['barbero_id', 'date', 'start_time', 'end_time', 'motivo'];
    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }
}
