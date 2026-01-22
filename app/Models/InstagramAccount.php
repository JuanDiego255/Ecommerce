<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramAccount extends Model
{
    protected $table = 'instagram_accounts';

    protected $fillable = [
        'user_id',
        'facebook_page_id',
        'facebook_page_access_token',
        'instagram_business_account_id',
        'instagram_username',
        'account_type',
        'is_active',
        'token_expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'token_expires_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(InstagramPost::class, 'instagram_account_id');
    }
}
