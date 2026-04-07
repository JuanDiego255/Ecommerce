<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesion_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_id')->constrained('sesiones_clinicas')->cascadeOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->enum('tipo', ['antes', 'durante', 'despues', 'referencia'])->default('durante');
            $table->string('path');
            $table->string('titulo')->nullable();
            $table->string('zona_corporal')->nullable(); // rostro, escote, cabello, etc.
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('es_favorita')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesion_imagenes');
    }
};
