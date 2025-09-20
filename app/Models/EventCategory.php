<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $fillable = ['event_id', 'nombre', 'edad_min', 'edad_max'];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
