<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'navbar', 'navbar_text', 'title_text', 'btn_cart', 'btn_cart_text',
        'footer', 'footer_text', 'sidebar', 'sidebar_text', 'hover', 'cart_icon',
        // Landing page
        'landing_primary', 'landing_secondary', 'landing_text_hero', 'landing_bg_section',
        'landing_hero_image', 'landing_hero_titulo', 'landing_hero_subtitulo',
        'landing_hero_btn_texto', 'landing_hero_btn_url',
        'landing_direccion', 'landing_horario',
    ];
}
