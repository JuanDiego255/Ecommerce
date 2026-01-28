<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramCaptionTemplate extends Model
{
    protected $fillable = [
        'name',
        'template_text',
        'is_active',
        'weight',
        'tenant_domain',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weight' => 'integer',
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

    /**
     * Selecciona una plantilla aleatoria basada en peso
     */
    public static function selectWeightedRandom()
    {
        $templates = static::active()->get();

        if ($templates->isEmpty()) {
            return null;
        }

        $totalWeight = $templates->sum('weight');

        if ($totalWeight <= 0) {
            return $templates->random();
        }

        $random = mt_rand(1, $totalWeight);
        $cumulative = 0;

        foreach ($templates as $template) {
            $cumulative += $template->weight;
            if ($random <= $cumulative) {
                return $template;
            }
        }

        return $templates->last();
    }
}
