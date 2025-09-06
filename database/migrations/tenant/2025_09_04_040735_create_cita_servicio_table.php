<?php
// database/migrations/2025_09_03_000004_create_cita_servicio_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('cita_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            $table->unsignedInteger('price_cents');
            $table->unsignedSmallInteger('duration_minutes');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('cita_servicio');
    }
};
