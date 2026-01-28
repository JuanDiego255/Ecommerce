<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramCollection extends Model
{
    protected $fillable = [
        'name',
        'notes',
        'status',
        'default_caption',
        'caption_template_id',
        'tenant_domain'
    ];

    public function items()
    {
        return $this->hasMany(InstagramCollectionItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function groups()
    {
        return $this->hasMany(InstagramCollectionGroup::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Relación con la plantilla de caption
     */
    public function captionTemplate()
    {
        return $this->belongsTo(InstagramCaptionTemplate::class, 'caption_template_id');
    }
}
