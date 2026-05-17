<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantsArtisanActive extends Command
{
    protected $signature = 'tenants:artisan-active
                            {commandstring : Comando artisan a ejecutar (ej: "migrate --path=database/migrations/tenant")}
                            {--tenant=* : Ejecutar solo en tenant(s) específicos activos (puede repetirse)}';

    protected $description = 'Ejecuta un comando Artisan únicamente en tenants con active = 1';

    public function handle(): int
    {
        $tenantIds = $this->option('tenant');

        $query = Tenant::where('active', 1);

        if (!empty($tenantIds)) {
            $query->whereIn('id', $tenantIds);
        }

        $tenants = $query->get();

        if ($tenants->isEmpty()) {
            $this->warn('No se encontraron tenants activos.');
            return self::SUCCESS;
        }

        $this->line("Tenants activos encontrados: {$tenants->count()}");
        $this->newLine();

        [$command, $params] = $this->parseCommandString($this->argument('commandstring'));

        foreach ($tenants as $tenant) {
            $this->line("<fg=cyan>── Tenant: {$tenant->id}</>");

            tenancy()->initialize($tenant);

            try {
                Artisan::call($command, $params, $this->output);
            } catch (\Throwable $e) {
                $this->error("  Error en {$tenant->id}: {$e->getMessage()}");
            }

            tenancy()->end();
            $this->newLine();
        }

        $this->info('Completado.');

        return self::SUCCESS;
    }

    private function parseCommandString(string $commandString): array
    {
        $parts   = preg_split('/\s+/', trim($commandString), 2);
        $command = $parts[0];
        $params  = [];

        if (isset($parts[1])) {
            preg_match_all('/--(\w[\w-]*)(?:=(\S+))?/', $parts[1], $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $params['--' . $match[1]] = isset($match[2]) && $match[2] !== '' ? $match[2] : true;
            }
        }

        return [$command, $params];
    }
}
