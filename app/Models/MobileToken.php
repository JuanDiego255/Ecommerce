<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MobileToken extends Model
{
    // Always use the 'central' connection — stancl/tenancy modifies the default
    // 'mysql' connection when a tenant is initialized, but never touches 'central'.
    protected $connection = 'central';

    protected $table = 'mobile_tokens';

    protected $fillable = ['name', 'token', 'last_used_at', 'is_active'];

    protected $casts = [
        'last_used_at' => 'datetime',
        'is_active'    => 'boolean',
    ];

    public static function generate(): array
    {
        $plain = Str::random(40);
        $hash  = hash('sha256', $plain);
        return ['plain' => $plain, 'hash' => $hash];
    }

    public static function validateToken(string $plain): bool
    {
        $hash   = hash('sha256', $plain);
        $record = static::where('token', $hash)->where('is_active', true)->first();

        if ($record) {
            $record->update(['last_used_at' => now()]);
            return true;
        }

        return false;
    }
}
