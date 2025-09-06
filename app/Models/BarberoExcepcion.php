<?php

// app/Models/BarberoExcepcion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberoExcepcion extends Model
{

    protected $table = 'barbero_excepciones';

    protected $fillable = ['barbero_id', 'date', 'motivo'];
    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }
}
