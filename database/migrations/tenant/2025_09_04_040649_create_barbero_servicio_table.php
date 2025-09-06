<?php
// database/migrations/2025_09_03_000002_create_barbero_servicio_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('barbero_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('barberos')->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            $table->unsignedInteger('price_cents')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->unique(['barbero_id', 'servicio_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('barbero_servicio');
    }
};
