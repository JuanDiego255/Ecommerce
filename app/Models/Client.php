<?php

// app/Models/Client.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'auto_book_opt_in',
        'preferred_barbero_id',
        'preferred_days',
        'preferred_start',
        'preferred_end',
        'last_seen_at',
        'notes',
        'cadence_days',
        'last_auto_booked_at',
        'next_due_at',
    ];
    protected $casts = [
        'auto_book_opt_in' => 'bool',
        'preferred_days'   => 'array',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'client_id');
    }

    public function preferredBarbero()
    {
        return $this->belongsTo(Barbero::class, 'preferred_barbero_id');
    }
}
