<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instagram_caption_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('template_text');
            $table->boolean('is_active')->default(true);
            $table->string('tenant_domain')->nullable();
            $table->timestamps();
        });

        // Agregar FK a instagram_collections
        Schema::table('instagram_collections', function (Blueprint $table) {
            $table->foreign('caption_template_id')
                ->references('id')
                ->on('instagram_caption_templates')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('instagram_collections', function (Blueprint $table) {
            $table->dropForeign(['caption_template_id']);
        });

        Schema::dropIfExists('instagram_caption_templates');
    }
};
