<?php

// app/Models/TenantSetting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    protected $fillable = [
        'tenant_id',
        'cancel_window_hours',
        'reschedule_window_hours',
        'allow_online_cancel',
        'allow_online_reschedule',
        'no_show_fee_cents',
        'email_bcc',
        'payroll_time'
    ];
}
