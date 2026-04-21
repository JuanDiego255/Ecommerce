<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpaceClient extends Model
{
    protected $fillable = ['name', 'payment_type', 'time_to_pay'];

    public function payments()
    {
        return $this->hasMany(SpacePayment::class, 'client_id');
    }
}
