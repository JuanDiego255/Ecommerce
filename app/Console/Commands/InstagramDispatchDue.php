<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InstagramPost;
use App\Domain\Instagram\Jobs\PublishInstagramPostJob;
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
