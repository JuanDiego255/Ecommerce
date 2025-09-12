<?php

// app/Console/Commands/AutoBookExpireHolds.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use App\Models\Tenant;

class AutoBookExpireHolds extends Command
{
    protected $signature = 'tenants:auto-book:expire-holds';
    protected $description = 'Cancela propuestas automáticas vencidas';

    public function handle(): int
    {
        $tenants = Tenant::get();
        foreach ($tenants as $tenant) {
            if ($tenant->id != "main") {
                tenancy()->initialize($tenant);
            } else {
                tenancy()->end();
            }
            $n = Cita::where('is_auto', true)->where('status', 'pending')
                ->whereNotNull('hold_expires_at')
                ->where('hold_expires_at', '<', now())
                ->update(['status' => 'cancelled']);
            $this->info("Propuestas vencidas canceladas para el tenant " . $tenant->id . ": $n");
        }
        return 0;
    }
}
