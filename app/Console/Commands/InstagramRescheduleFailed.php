<?php

namespace App\Console\Commands;

use App\Domain\Instagram\Jobs\PublishInstagramPostJob;
use App\Models\InstagramPost;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Re-dispatch Instagram posts that failed during scheduled publishing.
 *
 * A post qualifies for auto-retry when:
 *   - status = 'failed'
 *   - originally had a scheduled_at (it was a planned post, not a manual "publish now")
 *   - it failed within the last WINDOW_HOURS hours (so we don't retry ancient failures)
 *   - it hasn't been auto-retried yet (auto_retried_at is null)
 *
 * On retry the post is reset to 'scheduled' and re-dispatched. If it fails again
 * it stays 'failed' permanently — no infinite loops.
 */
class InstagramRescheduleFailed extends Command
{
    protected $signature = 'instagram:reschedule-failed
                            {--window=4 : Only retry posts that failed within this many hours}
                            {--delay=30 : Reschedule to now + this many minutes}
                            {--dry-run : Show what would be retried without changing anything}';

    protected $description = 'Auto-retry recently failed scheduled Instagram posts (one attempt each).';

    public function handle(): int
    {
        $windowHours = (int) $this->option('window');
        $delayMin    = (int) $this->option('delay');
        $dryRun      = $this->option('dry-run');

        $cutoff = now()->subHours($windowHours);

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            if ($tenant->id !== 'main') {
                tenancy()->initialize($tenant);
            } else {
                tenancy()->end();
            }

            // Only auto-retry posts that:
            // 1. Are failed AND had an original schedule (not manual publish-now posts)
            // 2. Failed recently (within the window)
            // 3. Have not yet been auto-retried (auto_retried_at is null)
            $posts = InstagramPost::where('status', 'failed')
                ->whereNotNull('scheduled_at')
                ->whereNull('auto_retried_at')
                ->where('updated_at', '>=', $cutoff)
                ->get();

            foreach ($posts as $post) {
                if ($dryRun) {
                    $this->line("[DRY-RUN] [{$tenant->id}] Post #{$post->id} sería reprogramado en {$delayMin} min.");
                    continue;
                }

                $newScheduledAt = now()->addMinutes($delayMin);

                $post->update([
                    'status'          => 'scheduled',
                    'scheduled_at'    => $newScheduledAt,
                    'error_message'   => null,
                    'auto_retried_at' => now(),
                ]);

                dispatch(new PublishInstagramPostJob($post->id));

                Log::info("Instagram auto-retry: tenant={$tenant->id} post_id={$post->id} new_scheduled={$newScheduledAt}");
                $this->info("[{$tenant->id}] Post #{$post->id} reprogramado para {$newScheduledAt->format('H:i')}.");
            }
        }

        $this->info('instagram:reschedule-failed completado.');
        return 0;
    }
}
