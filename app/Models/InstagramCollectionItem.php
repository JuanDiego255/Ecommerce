<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramCollectionItem extends Model
{
    protected $fillable = [
        'instagram_collection_id',
        'sort_order',
        'image_path',
        'original_name'
    ];

    public function collection()
    {
        return $this->belongsTo(InstagramCollection::class, 'instagram_collection_id');
    }

    public function getImageUrlAttribute(): string
    {
        return 'https://' . ($this->collection?->tenant_domain ?? request()->getHost()) . '/file/' . ltrim($this->image_path, '/');
    }
    public function group()
    {
        return $this->belongsTo(InstagramCollectionGroup::class, 'group_id');
    }
}
