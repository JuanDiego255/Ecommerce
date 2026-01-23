<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramCollectionGroup extends Model
{
    protected $fillable = [
        'instagram_collection_id',
        'instagram_post_id',
        'name',
        'sort_order'
    ];

    public function collection()
    {
        return $this->belongsTo(InstagramCollection::class);
    }

    public function items()
    {
        return $this->hasMany(InstagramCollectionItem::class, 'group_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
    public function post()
    {
        return $this->belongsTo(\App\Models\InstagramPost::class, 'instagram_post_id');
    }
}
