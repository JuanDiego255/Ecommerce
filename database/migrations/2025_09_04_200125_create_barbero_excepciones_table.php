<?php

// database/migrations/2025_09_04_000310_create_barbero_excepciones_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barbero_excepciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('barberos')->cascadeOnDelete();
            $table->date('date'); // YYYY-MM-DD (día completo sin atender)
            $table->string('motivo')->nullable();
            $table->timestamps();
            $table->unique(['barbero_id', 'date']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('barbero_excepciones');
    }
};
