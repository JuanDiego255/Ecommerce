<?php

namespace App\Domain\Instagram\Services;

use App\Models\InstagramPost;
use Illuminate\Support\Facades\Http;

class InstagramPublishService
{
    public function __construct(
        private readonly InstagramPrePublishValidator $validator
    ) {}

    /**
     * Max polling attempts and sleep between each (seconds) when waiting
     * for a container to reach FINISHED status before publishing.
     */
    private const POLL_MAX     = 12;
    private const POLL_SLEEP   = 5;   // seconds between polls
    private const CHILD_SLEEP  = 3;   // initial wait after creating all child containers

    public function publishFeed(InstagramPost $post): array
    {
        $post->loadMissing(['account', 'media']);

        // Pre-flight validation: fail fast before touching the Instagram API.
        $this->validator->validate($post);

        $token    = $post->account->facebook_page_access_token;
        $igUserId = $post->account->instagram_business_account_id;

        // ── Single image ─────────────────────────────────────────────────
        if ($post->media->count() === 1) {
            $containerId = $this->createImageContainer(
                igUserId: $igUserId,
                token:    $token,
                imageUrl: $this->buildTenantFileUrl($post, $post->media->first()->media_path),
                caption:  (string) $post->caption
            );

            $this->waitUntilContainerReady($igUserId, $token, $containerId);

            $mediaId = $this->publishContainer($igUserId, $token, $containerId);

            return ['container_id' => $containerId, 'media_id' => $mediaId];
        }

        // ── Carousel (up to 10 images) ───────────────────────────────────
        // Idempotency: reuse any container IDs that were already created on a
        // previous (failed) attempt so we don't create orphan containers.
        $children = [];
        foreach ($post->media->take(10) as $m) {
            if (!empty($m->meta_container_id)) {
                // Reuse the container from the previous attempt
                $children[] = $m->meta_container_id;
                continue;
            }

            $childId = $this->createCarouselItemContainer(
                igUserId: $igUserId,
                token:    $token,
                imageUrl: $this->buildTenantFileUrl($post, $m->media_path)
            );

            // Persist the container ID so retries can reuse it
            $m->update(['meta_container_id' => $childId]);

            $children[] = $childId;
        }

        // Give Instagram time to register all child containers internally
        // before creating the carousel parent container.
        sleep(self::CHILD_SLEEP);

        $carouselContainerId = $this->createCarouselContainer(
            igUserId: $igUserId,
            token:    $token,
            children: $children,
            caption:  (string) $post->caption
        );

        // Poll until the carousel container is FINISHED processing
        // (Instagram encodes + validates all images asynchronously).
        $this->waitUntilContainerReady($igUserId, $token, $carouselContainerId);

        $mediaId = $this->publishContainer($igUserId, $token, $carouselContainerId);

        return ['container_id' => $carouselContainerId, 'media_id' => $mediaId];
    }

    // ── Private helpers ──────────────────────────────────────────────────

    /**
     * Poll the container status until it is FINISHED (ready to publish).
     *
     * Instagram processes images asynchronously after creating a container.
     * Possible status_code values: IN_PROGRESS, FINISHED, ERROR, PUBLISHED, EXPIRED.
     *
     * @throws \Exception if the container reports ERROR or doesn't finish in time.
     */
    private function waitUntilContainerReady(string $igUserId, string $token, string $containerId): void
    {
        for ($attempt = 1; $attempt <= self::POLL_MAX; $attempt++) {
            sleep(self::POLL_SLEEP);

            $resp = Http::get($this->graphUrl("/{$containerId}"), [
                'fields'       => 'status_code,status',
                'access_token' => $token,
            ]);

            if (!$resp->ok()) {
                throw new \Exception(
                    "Error verificando estado del container [{$containerId}]: " . $resp->body()
                );
            }

            $statusCode = $resp->json('status_code');

            if ($statusCode === 'FINISHED') {
                return;
            }

            if ($statusCode === 'ERROR') {
                $detail = $resp->json('status') ?? 'sin detalle';
                throw new \Exception(
                    "Container [{$containerId}] falló con estado ERROR: {$detail}"
                );
            }

            // IN_PROGRESS, PUBLISHED (already published somehow), or unknown → keep waiting
        }

        throw new \Exception(
            "El container [{$containerId}] no alcanzó FINISHED después de " .
            (self::POLL_MAX * self::POLL_SLEEP) . " segundos. Intenta de nuevo."
        );
    }

    private function createImageContainer(string $igUserId, string $token, string $imageUrl, string $caption): string
    {
        $resp = Http::asForm()->post($this->graphUrl("/{$igUserId}/media"), [
            'image_url'    => $imageUrl,
            'caption'      => $caption,
            'access_token' => $token,
        ]);

        if (!$resp->ok()) {
            throw new \Exception('Error creando container (single): ' . $resp->body());
        }

        $id = $resp->json('id');
        if (!$id) throw new \Exception('Container ID inválido (single).');

        return $id;
    }

    private function createCarouselItemContainer(string $igUserId, string $token, string $imageUrl): string
    {
        $resp = Http::asForm()->post($this->graphUrl("/{$igUserId}/media"), [
            'image_url'        => $imageUrl,
            'is_carousel_item' => 'true',
            'access_token'     => $token,
        ]);

        if (!$resp->ok()) {
            throw new \Exception('Error creando container (carousel item): ' . $resp->body());
        }

        $id = $resp->json('id');
        if (!$id) throw new \Exception('Container ID inválido (carousel item).');

        return $id;
    }

    private function createCarouselContainer(string $igUserId, string $token, array $children, string $caption): string
    {
        $resp = Http::asForm()->post($this->graphUrl("/{$igUserId}/media"), [
            'media_type'   => 'CAROUSEL',
            'children'     => implode(',', $children),
            'caption'      => $caption,
            'access_token' => $token,
        ]);

        if (!$resp->ok()) {
            throw new \Exception('Error creando container (carousel): ' . $resp->body());
        }

        $id = $resp->json('id');
        if (!$id) throw new \Exception('Container ID inválido (carousel).');

        return $id;
    }

    private function publishContainer(string $igUserId, string $token, string $containerId): string
    {
        $resp = Http::asForm()->post($this->graphUrl("/{$igUserId}/media_publish"), [
            'creation_id'  => $containerId,
            'access_token' => $token,
        ]);

        if (!$resp->ok()) {
            throw new \Exception('Error publicando container: ' . $resp->body());
        }

        $id = $resp->json('id');
        if (!$id) throw new \Exception('Media ID inválido al publicar.');

        return $id;
    }

    private function graphUrl(string $path): string
    {
        $version = config('meta.graph_version', 'v19.0');
        return "https://graph.facebook.com/{$version}{$path}";
    }

    private function buildTenantFileUrl(InstagramPost $post, string $mediaPath): string
    {
        $domain = $post->tenant_domain;

        if (!$domain) {
            throw new \Exception('tenant_domain no está definido en instagram_posts.');
        }

        return 'https://' . $domain . '/file/' . ltrim($mediaPath, '/');
    }
}
