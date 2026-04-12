<?php

namespace App\Console\Commands;

use App\Models\InstagramPost;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Reset Instagram posts that are stuck in "publishing" status for too long.
 *
 * A post is considered a zombie if it has been in "publishing" state for more
 * than ZOMBIE_MINUTES minutes — meaning the job likely crashed without updating
 * the record, leaving the post in limbo forever.
 */
class InstagramCleanZombiePosts extends Command
{
    protected $signature = 'instagram:clean-zombies
                            {--minutes=15 : Posts stuck in "publishing" longer than this many minutes}
                            {--dry-run : List zombies without modifying them}';

    protected $description = 'Reset Instagram posts stuck in "publishing" status to "failed".';

    public function handle(): int
    {
        $minutes = (int) $this->option('minutes');
        $dryRun  = $this->option('dry-run');
        $cutoff  = now()->subMinutes($minutes);

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            if ($tenant->id !== 'main') {
                tenancy()->initialize($tenant);
            } else {
                tenancy()->end();
            }

            $zombies = InstagramPost::where('status', 'publishing')
                ->where('updated_at', '<=', $cutoff)
                ->get();

            if ($zombies->isEmpty()) {
                continue;
            }

            foreach ($zombies as $post) {
                $age = now()->diffInMinutes($post->updated_at);

                if ($dryRun) {
                    $this->line("[DRY-RUN] [{$tenant->id}] Post #{$post->id} lleva {$age} min en 'publishing'.");
                    continue;
                }

                $post->update([
                    'status' => 'failed',
                    'error_message' => "Publicación interrumpida (proceso zombie de {$age} min). Reintenta desde el panel.",
                ]);

                Log::warning("Instagram zombie cleaned: tenant={$tenant->id} post_id={$post->id} age={$age}min");
                $this->info("[{$tenant->id}] Post #{$post->id} ({$age} min) → failed.");
            }
        }

        $this->info('instagram:clean-zombies completado.');
        return 0;
    }
}
