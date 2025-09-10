<?php

// app/Console/Commands/AutoBookExpireHolds.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;

class AutoBookExpireHolds extends Command
{
    protected $signature = 'auto-book:expire-holds';
    protected $description = 'Cancela propuestas automáticas vencidas';

    public function handle(): int
    {
        $n = Cita::where('is_auto', true)->where('status', 'pending')
            ->whereNotNull('hold_expires_at')
            ->where('hold_expires_at', '<', now())
            ->update(['status' => 'cancelled']);
        $this->info("Propuestas vencidas canceladas: $n");
        return 0;
    }
}
