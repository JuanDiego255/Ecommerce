<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Jobs\ProposeAutoAppointmentJob;
use App\Support\TenantSettings;

class TenantsAutoBookRun extends Command
{
    protected $signature = 'tenants:auto-book-run {--force}';
    protected $description = 'Evalúa clientes opt-in y propone citas si corresponde';

    public function handle(): int
    {
        // Si usas stancl/tenancy, corre esto por tenant.
        // Aquí un ejemplo simple por el tenant actual (ajusta a tu helper de tenants:each)
        $tenantId = tenant('id') ?? config('app.name'); // ajusta según tu tenancy
        $tenant = TenantSettings::get($tenantId);
        if (!$tenant || !$tenant->auto_book_enabled) {
            $this->info('Auto-book desactivado en este tenant.');
            return 0;
        }

        $now = now();
        $clientes = Client::where('auto_book_opt_in', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('next_due_at')->orWhere('next_due_at', '<=', $now);
            })
            ->limit(200) // evita colas enormes
            ->get(['id']);

        foreach ($clientes as $c) {
            ProposeAutoAppointmentJob::dispatch($c->id)->onQueue('emails'); // o la que uses
        }

        $this->info("Propuestas encoladas: " . $clientes->count());
        return 0;
    }
}
