<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Jobs\ProposeAutoAppointmentJob;
use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\TenantSetting;
use App\Support\TenantSettings;

class TenantsAutoBookRun extends Command
{
    protected $signature = 'tenants:auto-book-run';
    protected $description = 'Evalúa clientes opt-in y propone citas si corresponde';

    public function handle(): int
    {
        $tenants = Tenant::get();

        foreach ($tenants as $tenant) {
            if ($tenant->id != "main") {
                tenancy()->initialize($tenant);
            } else {
                tenancy()->end();
            }
            $tenantinfo = TenantInfo::first();
            $tenantId = $tenantinfo->tenant;
            $tenant_set = TenantSetting::where('tenant_id', $tenantId)->first();

            if (!$tenant_set || !$tenant_set->auto_book_enabled) {
                $this->info('Auto-book desactivado en este tenant.');
                continue;
            }

            $now = now();
            $clientes = Client::where('auto_book_opt_in', 1)
                ->where(function ($q) {
                    $q->whereIn('auto_book_frequency', ['weekly', 'biweekly'])
                        ->orWhereIn('cadence_days', [7, 14]);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('next_due_at')->orWhere('next_due_at', '<=', $now);
                })
                ->limit(200) // evita colas enormes
                ->get(['id']);

            foreach ($clientes as $c) {
                ProposeAutoAppointmentJob::dispatch($c->id)->onQueue('emails'); // o la que uses
            }
            $this->info("Propuestas encoladas para el tenant " . $tenant->id . ":" . $clientes->count());
        }

        return 0;
    }
}
