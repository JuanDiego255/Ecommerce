<?php

// database/migrations/2025_09_04_000320_create_barbero_bloques_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barbero_bloques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('barberos')->cascadeOnDelete();
            $table->date('date');           // día al que aplica
            $table->time('start_time');     // hh:mm:ss
            $table->time('end_time');       // hh:mm:ss
            $table->string('motivo')->nullable();
            $table->timestamps();
            $table->index(['barbero_id', 'date']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('barbero_bloques');
    }
};
