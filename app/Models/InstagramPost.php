<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramPost extends Model
{
    protected $table = 'instagram_posts';

    protected $fillable = [
        'instagram_account_id',
        'clothing_id',
        'type',
        'caption',
        'status',
        'scheduled_at',
        'published_at',
        'meta_container_id',
        'meta_media_id',
        'error_message',
        'tenant_domain'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(InstagramAccount::class, 'instagram_account_id');
    }

    public function media()
    {
        return $this->hasMany(InstagramPostMedia::class, 'instagram_post_id')
            ->orderBy('sort_order');
    }

    // Helpers rápidos (útiles en vistas)
    public function isFeed(): bool
    {
        return $this->type === 'feed';
    }

    public function isStory(): bool
    {
        return $this->type === 'story';
    }
}
