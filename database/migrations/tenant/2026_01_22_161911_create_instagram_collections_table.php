<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instagram_collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->string('status')->default('draft'); // draft|ready|scheduled|publishing|published
            $table->text('default_caption')->nullable();
            $table->unsignedBigInteger('caption_template_id')->nullable();
            $table->string('tenant_domain')->nullable();

            $table->timestamps();

            // Si luego creas templates: FK opcional
            // $table->foreign('caption_template_id')->references('id')->on('instagram_caption_templates')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_collections');
    }
};
