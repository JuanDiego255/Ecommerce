<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramCaptionSettings extends Model
{
    protected $table = 'instagram_caption_settings';

    protected $fillable = [
        'hashtag_pool_id',
        'auto_select_template',
        'auto_add_hashtags',
        'auto_add_cta',
        'max_hashtags',
        'queue_interval_hours',
        'queue_start_hour',
        'queue_end_hour',
        'tenant_domain',
    ];

    protected $casts = [
        'auto_select_template' => 'boolean',
        'auto_add_hashtags' => 'boolean',
        'auto_add_cta' => 'boolean',
        'max_hashtags' => 'integer',
        'queue_interval_hours' => 'integer',
    ];

    public function hashtagPool()
    {
        return $this->belongsTo(InstagramHashtagPool::class, 'hashtag_pool_id');
    }

    /**
     * Obtiene o crea la configuración para el tenant actual
     */
    public static function getOrCreate(?string $tenantDomain = null): self
    {
        $tenantDomain = $tenantDomain ?? request()->getHost();

        return static::firstOrCreate(
            ['tenant_domain' => $tenantDomain],
            [
                'auto_select_template' => true,
                'auto_add_hashtags' => true,
                'auto_add_cta' => true,
                'max_hashtags' => 15,
                'queue_interval_hours' => 4,
                'queue_start_hour' => '09:00',
                'queue_end_hour' => '21:00',
            ]
        );
    }
}
