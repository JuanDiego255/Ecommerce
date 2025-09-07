<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_id',
        'barbero_id',
        'services_count',
        'gross_cents',
        'commission_rate',
        'barber_commission_cents',
        'owner_commission_cents',
        'adjustment_cents',
        'paid_at'
    ];
    protected $casts = ['paid_at' => 'datetime'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
    public function barbero()
    {
        return $this->belongsTo(Barbero::class);
    }

    public function getFinalBarberCentsAttribute(): int
    {
        return $this->barber_commission_cents + $this->adjustment_cents;
    }
}
