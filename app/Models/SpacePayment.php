<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpacePayment extends Model
{
    protected $fillable = ['client_id', 'amount', 'description', 'payment_date'];

    public function client()
    {
        return $this->belongsTo(SpaceClient::class, 'client_id');
    }
}
