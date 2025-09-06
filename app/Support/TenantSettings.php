<?php

// app/Support/TenantSettings.php
namespace App\Support;

use App\Models\TenantSetting;
use Illuminate\Support\Facades\Cache;

class TenantSettings
{
    public static function get(string $tenantId): TenantSetting
    {
        return Cache::remember("tenant_settings:{$tenantId}", 300, function () use ($tenantId) {
            return TenantSetting::firstOrCreate(['tenant_id' => $tenantId]);
        });
    }
}
