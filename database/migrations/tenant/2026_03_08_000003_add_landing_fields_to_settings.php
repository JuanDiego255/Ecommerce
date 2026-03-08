<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Colores y estilo para landing pages
            $table->string('landing_primary', 30)->nullable();     // Color primario (ej: #1a1a2e)
            $table->string('landing_secondary', 30)->nullable();   // Color secundario (ej: #c9a84c)
            $table->string('landing_text_hero', 30)->nullable();   // Color texto hero
            $table->string('landing_bg_section', 30)->nullable();  // Color fondo secciones alternas
            // Hero (Inicio)
            $table->string('landing_hero_image', 300)->nullable(); // Imagen de fondo hero
            $table->string('landing_hero_titulo', 200)->nullable();
            $table->text('landing_hero_subtitulo')->nullable();
            $table->string('landing_hero_btn_texto', 100)->nullable();
            $table->string('landing_hero_btn_url', 300)->nullable();
            // Contacto
            $table->string('landing_direccion', 300)->nullable();
            $table->string('landing_horario', 200)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'landing_primary', 'landing_secondary', 'landing_text_hero', 'landing_bg_section',
                'landing_hero_image', 'landing_hero_titulo', 'landing_hero_subtitulo',
                'landing_hero_btn_texto', 'landing_hero_btn_url',
                'landing_direccion', 'landing_horario',
            ]);
        });
    }
};
