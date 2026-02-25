<?php

namespace App\Console\Commands;

use App\Jobs\ProposeAutoAppointmentJob;
use App\Models\Client;
use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\TenantSetting;
use Illuminate\Console\Command;

class TenantsAutoBookRun extends Command
{
    protected $signature   = 'tenants:auto-book-run';
    protected $description = 'Evalúa clientes opt-in cuya cita ya llegó y agenda la siguiente automáticamente';

    public function handle(): int
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            if ($tenant->id !== 'main') {
                tenancy()->initialize($tenant);
            } else {
                tenancy()->end();
            }

            $tenantinfo = TenantInfo::first();
            if (!$tenantinfo) {
                continue;
            }

            $tenantId  = $tenantinfo->tenant;
            $tenantSet = TenantSetting::where('tenant_id', $tenantId)->first();

            if (!$tenantSet || !$tenantSet->auto_book_enabled) {
                $this->info("[{$tenantId}] Auto-book desactivado. Se omite.");
                continue;
            }

            $now = now();

            /*
             * Criterios de selección:
             *
             * 1. auto_book_opt_in = 1
             * 2. Frecuencia semanal o quincenal
             * 3. next_due_at IS NULL  (primer ciclo, nunca agendado)
             *    OR next_due_at ≤ now (la cita referenciada ya llegó)
             *    → next_due_at se establece al starts_at de la cita recién creada,
             *      por lo que actúa como "no volver a disparar hasta que esa cita llegue".
             * 4. Sin cita auto futura ya confirmada (guarda de doble-disparo si el job
             *    fue encolado pero aún no actualizó next_due_at).
             */
            $clientes = Client::where('auto_book_opt_in', 1)
                ->where(function ($q) {
                    $q->whereIn('auto_book_frequency', ['weekly', 'biweekly'])
                      ->orWhereIn('cadence_days', [7, 14]);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('next_due_at')
                      ->orWhere('next_due_at', '<=', $now);
                })
                ->whereDoesntHave('citas', function ($q) use ($now) {
                    $q->where('is_auto', true)
                      ->where('status', 'confirmed')
                      ->where('starts_at', '>', $now);
                })
                ->limit(200)
                ->get(['id']);

            foreach ($clientes as $c) {
                ProposeAutoAppointmentJob::dispatch($c->id)->onQueue('emails');
            }

            $this->info("[{$tenantId}] Propuestas encoladas: {$clientes->count()}");
        }

        return self::SUCCESS;
    }
}
