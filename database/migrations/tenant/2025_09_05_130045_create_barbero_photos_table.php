<?php

// database/migrations/2025_09_05_000002_create_barbero_photos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barbero_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained()->cascadeOnDelete();
            $table->string('path');            // ruta imagen
            $table->string('thumb_path')->nullable(); // miniatura (opcional)
            $table->string('caption')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('barbero_photos');
    }
};
