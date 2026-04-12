<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InstagramPost;
use App\Domain\Instagram\Jobs\PublishInstagramPostJob;
use App\Models\InstagramAccount;
use App\Models\Tenant;

class InstagramDispatchDue extends Command
{
    protected $signature = 'tenants:auto-post:instagram';
    protected $description = 'Dispatch scheduled Instagram posts that are due (current tenant DB).';

    public function handle(): int
    {
        $now = now();
        $tenants = Tenant::get();
        foreach ($tenants as $tenant) {
            if ($tenant->id != "main") {
                tenancy()->initialize($tenant);
            } else {
                tenancy()->end();
            }
            // Check if the active account has an expired token before dispatching.
            $account = InstagramAccount::where('is_active', true)->latest()->first();
            if ($account && $account->token_expires_at && $account->token_expires_at->isPast()) {
                $this->warn("[{$tenant->id}] Token de Instagram expirado (venció {$account->token_expires_at->format('d/m/Y')}). Se omiten posts programados.");
                continue;
            }

            // Rate-limit guard: skip dispatch if already at 25 publications in the last 24h.
            if ($account) {
                $publishedToday = InstagramPost::where('instagram_account_id', $account->id)
                    ->where('status', 'published')
                    ->where('published_at', '>=', now()->subDay())
                    ->count();

                if ($publishedToday >= 25) {
                    $this->warn("[{$tenant->id}] Límite diario de 25 publicaciones alcanzado ({$publishedToday}/25). No se despachan más posts hoy.");
                    continue;
                }
            }

            $posts = InstagramPost::where('status', 'scheduled')
                ->whereNotNull('scheduled_at')
                ->where('scheduled_at', '<=', $now)
                ->orderBy('scheduled_at')
                ->limit(15)
                ->get();

            foreach ($posts as $post) {
                dispatch(new PublishInstagramPostJob($post->id));
            }
            $this->info("count = {$posts->count()} now = {$now}");
        }

        return 0;
    }
}
