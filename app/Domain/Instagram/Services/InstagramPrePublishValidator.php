<?php

namespace App\Domain\Instagram\Services;

use App\Models\InstagramPost;
use Illuminate\Support\Facades\Http;

class InstagramPrePublishValidator
{
    private const MAX_CAPTION_LENGTH  = 2200;
    private const MIN_IMAGES          = 1;
    private const MAX_IMAGES          = 10;
    private const RATE_LIMIT_PER_DAY  = 25;   // Instagram Graph API limit

    /**
     * Run all pre-publish validations before touching the Instagram API.
     *
     * @throws \Exception with a clear, user-facing message on any failure.
     */
    public function validate(InstagramPost $post): void
    {
        $post->loadMissing(['account', 'media']);

        $this->validateAccount($post);
        $this->validateTenantDomain($post);
        $this->validateCaption($post);
        $this->validateImageCount($post);
        $this->validateRateLimit($post);
        $this->validateImageUrls($post);
    }

    // ── Private checks ───────────────────────────────────────────────────

    private function validateAccount(InstagramPost $post): void
    {
        if (!$post->account) {
            throw new \Exception('No hay cuenta de Instagram vinculada al post.');
        }

        if (!$post->account->facebook_page_access_token) {
            throw new \Exception('La cuenta de Instagram no tiene token de acceso.');
        }

        if (!$post->account->instagram_business_account_id) {
            throw new \Exception('La cuenta de Instagram no tiene ID de cuenta de negocio.');
        }

        // Check token expiry if we have that info
        if ($post->account->token_expires_at && $post->account->token_expires_at->isPast()) {
            throw new \Exception(
                'El token de Instagram ha expirado (venció el ' .
                $post->account->token_expires_at->format('d/m/Y') .
                '). Reconecta la cuenta desde la sección de Instagram.'
            );
        }
    }

    private function validateTenantDomain(InstagramPost $post): void
    {
        if (empty($post->tenant_domain)) {
            throw new \Exception(
                'El post no tiene tenant_domain configurado. No se pueden construir las URLs de las imágenes.'
            );
        }
    }

    private function validateCaption(InstagramPost $post): void
    {
        $caption = (string) $post->caption;

        if (mb_strlen($caption) > self::MAX_CAPTION_LENGTH) {
            throw new \Exception(
                'El caption excede el límite de ' . self::MAX_CAPTION_LENGTH .
                ' caracteres de Instagram (' . mb_strlen($caption) . ' caracteres).'
            );
        }
    }

    private function validateImageCount(InstagramPost $post): void
    {
        $count = $post->media->count();

        if ($count < self::MIN_IMAGES) {
            throw new \Exception('La publicación no tiene imágenes.');
        }

        if ($count > self::MAX_IMAGES) {
            throw new \Exception(
                "La publicación tiene {$count} imágenes pero Instagram permite un máximo de " .
                self::MAX_IMAGES . ' por publicación.'
            );
        }
    }

    private function validateRateLimit(InstagramPost $post): void
    {
        $accountId = $post->account->id;

        $publishedLast24h = InstagramPost::where('instagram_account_id', $accountId)
            ->where('status', 'published')
            ->where('published_at', '>=', now()->subDay())
            ->count();

        if ($publishedLast24h >= self::RATE_LIMIT_PER_DAY) {
            throw new \Exception(
                "Límite de publicaciones alcanzado: ya se publicaron {$publishedLast24h} posts en las últimas 24 horas. " .
                'Instagram permite un máximo de ' . self::RATE_LIMIT_PER_DAY . ' publicaciones por día. ' .
                'Intenta de nuevo mañana.'
            );
        }
    }

    private function validateImageUrls(InstagramPost $post): void
    {
        foreach ($post->media->take(self::MAX_IMAGES) as $index => $m) {
            $url = $this->buildImageUrl($post->tenant_domain, $m->media_path);
            $num = $index + 1;

            try {
                $resp = Http::timeout(8)->head($url);

                if ($resp->status() === 404) {
                    throw new \Exception(
                        "Imagen #{$num} no encontrada (404): {$url}"
                    );
                }

                if (!$resp->successful()) {
                    throw new \Exception(
                        "Imagen #{$num} no accesible (HTTP {$resp->status()}): {$url}"
                    );
                }

                // Verify it is actually an image content type
                $contentType = $resp->header('Content-Type') ?? '';
                if ($contentType && !str_starts_with($contentType, 'image/')) {
                    throw new \Exception(
                        "La URL de imagen #{$num} no devuelve una imagen (Content-Type: {$contentType}): {$url}"
                    );
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                throw new \Exception(
                    "No se pudo verificar la imagen #{$num} (error de conexión). " .
                    "Asegúrate de que el dominio {$post->tenant_domain} sea accesible públicamente."
                );
            }
        }
    }

    private function buildImageUrl(string $tenantDomain, string $mediaPath): string
    {
        return 'https://' . $tenantDomain . '/file/' . ltrim($mediaPath, '/');
    }
}
