<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramPostMedia extends Model
{
    protected $table = 'instagram_post_media';

    protected $fillable = [
        'instagram_post_id',
        'sort_order',
        'media_type',
        'media_path',
    ];

    public function post()
    {
        return $this->belongsTo(InstagramPost::class, 'instagram_post_id');
    }

    /**
     * URL pública (según tu route('file', $path)).
     * Meta necesita una URL accesible públicamente (ideal con https).
     */
    public function getMediaUrlAttribute(): string
    {
        return route('file', $this->media_path);
    }
}
