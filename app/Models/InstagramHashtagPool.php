<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramHashtagPool extends Model
{
    protected $fillable = [
        'name',
        'hashtags',
        'max_hashtags',
        'shuffle',
        'is_active',
        'tenant_domain',
    ];

    protected $casts = [
        'max_hashtags' => 'integer',
        'shuffle' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtiene los hashtags como array
     */
    public function getHashtagsArray(): array
    {
        $text = $this->hashtags ?? '';

        // Soporta separación por coma, espacio o línea nueva
        $hashtags = preg_split('/[\s,\n]+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Asegurar que cada hashtag empiece con #
        return array_map(function ($tag) {
            $tag = trim($tag);
            return str_starts_with($tag, '#') ? $tag : '#' . $tag;
        }, $hashtags);
    }

    /**
     * Genera un conjunto de hashtags mezclados y limitados
     */
    public function generateHashtags(?int $limit = null): array
    {
        $hashtags = $this->getHashtagsArray();
        $limit = $limit ?? $this->max_hashtags;

        if ($this->shuffle) {
            shuffle($hashtags);
        }

        return array_slice($hashtags, 0, $limit);
    }

    /**
     * Genera hashtags como string
     */
    public function generateHashtagsString(?int $limit = null): string
    {
        return implode(' ', $this->generateHashtags($limit));
    }
}
