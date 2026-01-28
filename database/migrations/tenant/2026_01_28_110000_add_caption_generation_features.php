<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar peso a las plantillas para selección ponderada
        Schema::table('instagram_caption_templates', function (Blueprint $table) {
            $table->unsignedInteger('weight')->default(1)->after('is_active');
        });

        // Tabla de grupos de hashtags
        Schema::create('instagram_hashtag_pools', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('hashtags'); // Almacena hashtags separados por coma o línea
            $table->unsignedInteger('max_hashtags')->default(10); // Límite por post
            $table->boolean('shuffle')->default(true); // Mezclar orden
            $table->boolean('is_active')->default(true);
            $table->string('tenant_domain')->nullable();
            $table->timestamps();
        });

        // Tabla de CTAs (Call To Action)
        Schema::create('instagram_ctas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('cta_text'); // Texto del CTA con posible spintax
            $table->enum('type', ['dm', 'whatsapp', 'store', 'link', 'other'])->default('other');
            $table->unsignedInteger('weight')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('tenant_domain')->nullable();
            $table->timestamps();
        });

        // Configuración global de generación de captions por tenant
        Schema::create('instagram_caption_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hashtag_pool_id')->nullable();
            $table->boolean('auto_select_template')->default(true);
            $table->boolean('auto_add_hashtags')->default(true);
            $table->boolean('auto_add_cta')->default(true);
            $table->unsignedInteger('max_hashtags')->default(15);
            $table->string('tenant_domain')->unique();
            $table->timestamps();

            $table->foreign('hashtag_pool_id')
                ->references('id')
                ->on('instagram_hashtag_pools')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_caption_settings');
        Schema::dropIfExists('instagram_ctas');
        Schema::dropIfExists('instagram_hashtag_pools');

        Schema::table('instagram_caption_templates', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};
