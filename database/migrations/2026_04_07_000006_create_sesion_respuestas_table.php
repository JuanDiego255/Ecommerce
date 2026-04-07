<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesion_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_id')->constrained('sesiones_clinicas')->cascadeOnDelete();
            $table->string('campo_key');      // UUID del campo en el JSON de plantilla
            $table->string('campo_tipo');     // tipo del campo para render
            $table->longText('valor')->nullable(); // JSON para respuestas complejas, texto para simples
            $table->timestamps();

            $table->unique(['sesion_id', 'campo_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesion_respuestas');
    }
};
