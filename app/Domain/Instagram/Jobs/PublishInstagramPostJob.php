<?php

namespace App\Domain\Instagram\Jobs;

use App\Domain\Instagram\Services\InstagramPublishService;
use App\Models\InstagramPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishInstagramPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $postId;

    public $tries = 3;
    public $backoff = [60, 180, 600]; // reintentos

    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    public function handle(InstagramPublishService $service): void
    {
        $post = InstagramPost::with(['account', 'media'])->find($this->postId);
        if (!$post) return;

        // Si ya está publicada o cancelada, no hacemos nada
        if (in_array($post->status, ['publishing','published','cancelled'])) return;

        $post->update([
            'status' => 'publishing',
            'error_message' => null,
        ]);

        try {
            // Solo feed por ahora (Stories fase 2)
            if ($post->type !== 'feed') {
                throw new \Exception('Tipo no soportado en MVP: ' . $post->type);
            }

            $result = $service->publishFeed($post);

            $post->update([
                'status' => 'published',
                'published_at' => now(),
                'meta_container_id' => $result['container_id'] ?? null,
                'meta_media_id' => $result['media_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            $post->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e; // para que el queue registre retry si aplica
        }
    }
}
