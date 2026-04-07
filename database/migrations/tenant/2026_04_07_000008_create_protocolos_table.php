<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protocolos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('categoria')->nullable();
            $table->integer('duracion_estimada_min')->nullable();
            $table->enum('nivel_dificultad', ['basico', 'intermedio', 'avanzado'])->default('basico');
            $table->text('contraindicaciones')->nullable();
            $table->json('materiales_necesarios')->nullable();
            $table->json('pasos')->nullable(); // [{orden, titulo, descripcion, imagen, duracion_min}]
            $table->text('notas_post')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protocolos');
    }
};
