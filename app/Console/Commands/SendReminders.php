<?php

// app/Console/Commands/SendReminders.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use App\Mail\AppointmentReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReminders extends Command
{
    protected $signature = 'send:reminders
        {--window=60 : Ventana en minutos alrededor de 24h}
        {--dry-run : Solo listar, no enviar}';

    protected $description = 'Enviar recordatorios 24h antes a clientes (por tenant)';

    public function handle(): int
    {
        $tz = config('app.timezone', 'America/Costa_Rica');
        $window = (int) $this->option('window');

        $center = Carbon::now($tz)->addDay();
        $from   = $center->copy()->subMinutes($window)->startOfMinute();
        $to     = $center->copy()->addMinutes($window)->endOfMinute();

        $utcFrom = $from->copy()->timezone('UTC');
        $utcTo   = $to->copy()->timezone('UTC');

        $q = Cita::with(['barbero'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNull('reminder_sent_at')
            ->whereBetween('starts_at', [$utcFrom, $utcTo]);

        $dry = (bool) $this->option('dry-run');
        $count = 0;

        $q->chunkById(200, function ($citas) use ($dry, &$count) {
            foreach ($citas as $cita) {
                $count++;
                if ($dry) {
                    $this->line("DRY → Cita #{$cita->id} a {$cita->cliente_email}");
                    continue;
                }
                if ($cita->cliente_email) {
                    Mail::to($cita->cliente_email)->queue(new AppointmentReminder($cita));
                }
                // marca como enviado para no repetir
                $cita->forceFill(['reminder_sent_at' => now('UTC')])->save();
            }
        });

        $this->info("Recordatorios procesados: {$count}" . ($dry ? ' (DRY RUN)' : ''));
        return self::SUCCESS;
    }
}
