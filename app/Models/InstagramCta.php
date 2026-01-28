<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramCta extends Model
{
    protected $table = 'instagram_ctas';

    protected $fillable = [
        'name',
        'cta_text',
        'type',
        'weight',
        'is_active',
        'tenant_domain',
    ];

    protected $casts = [
        'weight' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Selecciona un CTA aleatorio basado en peso
     */
    public static function selectWeightedRandom()
    {
        $ctas = static::active()->get();

        if ($ctas->isEmpty()) {
            return null;
        }

        $totalWeight = $ctas->sum('weight');

        if ($totalWeight <= 0) {
            return $ctas->random();
        }

        $random = mt_rand(1, $totalWeight);
        $cumulative = 0;

        foreach ($ctas as $cta) {
            $cumulative += $cta->weight;
            if ($random <= $cumulative) {
                return $cta;
            }
        }

        return $ctas->last();
    }

    /**
     * Tipos de CTA disponibles
     */
    public static function types(): array
    {
        return [
            'dm' => 'Mensaje Directo (DM)',
            'whatsapp' => 'WhatsApp',
            'store' => 'Tienda Online',
            'link' => 'Link en Bio',
            'other' => 'Otro',
        ];
    }
}
