<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MobileToken extends Model
{
    protected $table = 'mobile_tokens';

    protected $fillable = ['name', 'token', 'last_used_at', 'is_active'];

    protected $casts = [
        'last_used_at' => 'datetime',
        'is_active'    => 'boolean',
    ];

    // Generate a cryptographically secure plain token and return both the
    // plain value (shown once) and its hash (stored in DB).
    public static function generate(): array
    {
        $plain = Str::random(40);
        $hash  = hash('sha256', $plain);
        return ['plain' => $plain, 'hash' => $hash];
    }

    // Validate a plain token from an HTTP header against the stored hashes.
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
