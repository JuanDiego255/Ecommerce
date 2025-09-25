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
    /**
     * Calcula el próximo next_due_at a partir de una fecha base (Carbon, en TZ local)
     * - Si tiene frecuencia semanal/quincenal (o cadence_days), respétala.
     * - Si no, usa default del tenant (o 30 días).
     * Devuelve un Carbon en UTC (si guardas UTC).
     */
    public function computeNextDueAtFromBase(\Carbon\Carbon $baseLocal, ?int $tenantDefault = 30, string $tz = 'America/Costa_Rica'): \Carbon\Carbon
    {
        $cadence = $this->effective_cadence_days ?? $tenantDefault ?? 30 - 3;
        $nextLocal = $baseLocal->copy()->addDays($cadence - 3);

        // (Opcional) anclar hora preferida si existe
        if ($this->preferred_start) {
            $hhmm = \Illuminate\Support\Str::substr((string)$this->preferred_start, 0, 5);
            $nextLocal->setTimeFromTimeString($hhmm);
        }

        return $nextLocal->clone();
    }
}
