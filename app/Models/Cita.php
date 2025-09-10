<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cita extends Model
{
    use HasFactory;


    protected $fillable = [
        'barbero_id',
        'user_id',
        'cliente_nombre',
        'cliente_email',
        'cliente_telefono',
        'starts_at',
        'ends_at',
        'total_cents',
        'status',
        'notas',
        'source',
        'client_id'
    ];


    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime'];


    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }


    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio')
            ->withPivot(['price_cents', 'duration_minutes'])
            ->withTimestamps();
    }
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }
}
