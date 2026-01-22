<?php

namespace App\Domain\Instagram\Services;

use App\Models\InstagramPost;
use Illuminate\Support\Facades\Http;

class InstagramPublishService
{
    public function publishFeed(InstagramPost $post): array
    {
        $post->loadMissing(['account', 'media']);

        if (!$post->account || !$post->account->facebook_page_access_token || !$post->account->instagram_business_account_id) {
            throw new \Exception('Cuenta Instagram no está correctamente conectada (token/page/ig_user faltante).');
        }

        if ($post->media->count() < 1) {
            throw new \Exception('La publicación no tiene imágenes.');
        }

        $token = $post->account->facebook_page_access_token;
        $igUserId = $post->account->instagram_business_account_id;

        // Single o carrusel
        if ($post->media->count() === 1) {
            $containerId = $this->createImageContainer(
                igUserId: $igUserId,
                token: $token,
                imageUrl: $this->buildTenantFileUrl($post, $post->media->first()->media_url),
                caption: (string) $post->caption
            );

            $mediaId = $this->publishContainer($igUserId, $token, $containerId);

            return ['container_id' => $containerId, 'media_id' => $mediaId];
        }

        // Carrusel (hasta 10)
        $children = [];
        foreach ($post->media->take(10) as $m) {
            $childContainerId = $this->createCarouselItemContainer(
                igUserId: $igUserId,
                token: $token,
                imageUrl: $this->buildTenantFileUrl($post, $m->media_url)
            );
            $children[] = $childContainerId;
        }

        $carouselContainerId = $this->createCarouselContainer(
            igUserId: $igUserId,
            token: $token,
            children: $children,
            caption: (string) $post->caption
        );

        $mediaId = $this->publishContainer($igUserId, $token, $carouselContainerId);

        return ['container_id' => $carouselContainerId, 'media_id' => $mediaId];
    }

    private function createImageContainer(string $igUserId, string $token, string $imageUrl, string $caption): string
    {
        $resp = Http::asForm()->post($this->graphUrl("/{$igUserId}/media"), [
            'image_url' => $imageUrl,
            'caption' => $caption,
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
            'image_url' => $imageUrl,
            'is_carousel_item' => 'true',
            'access_token' => $token,
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
            'media_type' => 'CAROUSEL',
            'children' => implode(',', $children),
            'caption' => $caption,
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
            'creation_id' => $containerId,
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
