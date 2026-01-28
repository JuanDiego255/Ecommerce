<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramCaptionTemplate extends Model
{
    protected $fillable = [
        'name',
        'template_text',
        'is_active',
        'tenant_domain',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function collections()
    {
        return $this->hasMany(InstagramCollection::class, 'caption_template_id');
    }

    /**
     * Scope para obtener solo plantillas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
