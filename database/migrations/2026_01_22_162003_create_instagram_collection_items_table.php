<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instagram_collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instagram_collection_id')->constrained('instagram_collections')->cascadeOnDelete();            
            $table->integer('sort_order')->default(0);
            $table->string('image_path');
            $table->string('original_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_collection_items');
    }
};
