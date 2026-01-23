<?php

namespace App\Domain\Instagram\Jobs;

use App\Domain\Instagram\Services\InstagramPublishService;
use App\Models\InstagramPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

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
        if (!$post) {
            return;
        }

        // Estados finales: no hacer nada
        if (in_array($post->status, ['published', 'cancelled'], true)) {
            return;
        }

        // Lock anti-concurrencia (evita doble publicación)
        // 180s para cubrir el flujo de containers/publish y latencia de Meta
        $lock = Cache::lock('ig_publish_post_' . $post->id, 180);

        if (!$lock->get()) {
            // Ya hay otro proceso publicando este post
            return;
        }

        try {
            // Validaciones MVP
            if ($post->type !== 'feed') {
                throw new \Exception('Tipo no soportado en MVP: ' . $post->type);
            }

            // Si está en draft/scheduled/failed -> lo pasamos a publishing
            // Si ya está publishing, lo dejamos (pero igual seguimos, porque el lock nos protege).
            if ($post->status !== 'publishing') {
                $post->update([
                    'status' => 'publishing',
                    'error_message' => null,
                ]);
            } else {
                // si estaba publishing y tenía error_message viejo, lo limpiamos
                if (!empty($post->error_message)) {
                    $post->update(['error_message' => null]);
                }
            }

            $result = $service->publishFeed($post);

            $post->update([
                'status' => 'published',
                'published_at' => now(),
                'meta_container_id' => $result['container_id'] ?? null,
                'meta_media_id' => $result['media_id'] ?? null,
                'error_message' => null,
            ]);
        } catch (\Throwable $e) {
            $post->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // Re-lanza para que el queue aplique retry/backoff
            throw $e;
        } finally {
            optional($lock)->release();
        }
    }
}
