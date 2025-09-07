<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = ['week_start', 'week_end', 'status', 'notes'];
    protected $casts = ['week_start' => 'date', 'week_end' => 'date'];

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }
}
