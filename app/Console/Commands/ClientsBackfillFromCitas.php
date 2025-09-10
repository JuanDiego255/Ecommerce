<?php

// app/Console/Commands/ClientsBackfillFromCitas.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Client, Cita};

class ClientsBackfillFromCitas extends Command
{
    protected $signature = 'clients:backfill-from-citas';
    protected $description = 'Crea/actualiza clients a partir de citas con email y enlaza client_id';

    public function handle(): int
    {
        $emails = Cita::whereNotNull('cliente_email')
            ->select('cliente_email')->distinct()->pluck('cliente_email');

        $count = 0;
        foreach ($emails as $email) {
            $first = Cita::where('cliente_email', $email)->oldest()->first();
            if (!$first) continue;

            $client = Client::firstOrCreate(['email' => $email], [
                'nombre' => $first->cliente_nombre,
                'telefono' => $first->cliente_phone,
                'last_seen_at' => $first->created_at ?? now(),
            ]);

            Cita::where('cliente_email', $email)
                ->whereNull('client_id')
                ->update(['client_id' => $client->id]);

            $count++;
        }
        $this->info("Clients creados/actualizados: {$count}");
        return 0;
    }
}
