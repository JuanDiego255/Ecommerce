<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_discount', 'title_instagram', 'mision', 'title_trend',
        'title_suscrib_a', 'description_suscrib', 'footer', 'logo', 'login_image',
        'whatsapp', 'sinpe', 'email', 'iva', 'delivery', 'tenant', 'count',
        'parameters', 'cintillo', 'text_cintillo', 'logo_ico', 'about_us',
        'kind_of_features', 'kind_business', 'license',
    ];

    // Indica si el tenant es de tipo landing page (página informativa)
    public function isLandingPage(): bool
    {
        return (int) $this->kind_business === 8;
    }
}
