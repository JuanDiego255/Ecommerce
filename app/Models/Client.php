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
        'discount',
        'auto_book_frequency',
        'auto_book_lookahead_days'
    ];
    protected $casts = [
        'auto_book_opt_in' => 'bool',
        'preferred_days'   => 'array',
        //'preferred_start'  => 'datetime:H:i',
        //'preferred_end'    => 'datetime:H:i',
        'next_due_at'      => 'datetime',
        'last_auto_booked_at' => 'datetime',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'client_id');
    }

    public function preferredBarbero()
    {
        return $this->belongsTo(Barbero::class, 'preferred_barbero_id');
    }
    public function getEffectiveCadenceDaysAttribute(): ?int
    {
        if ($this->cadence_days) return (int) $this->cadence_days;

        return match ($this->auto_book_frequency) {
            'weekly'   => 7,
            'biweekly' => 14,
            default    => null,
        };
    }

    public function prefersWeekly(): bool
    {
        return $this->auto_book_frequency === 'weekly' || $this->effective_cadence_days === 7;
    }

    public function prefersBiweekly(): bool
    {
        return $this->auto_book_frequency === 'biweekly' || $this->effective_cadence_days === 14;
    }
}
