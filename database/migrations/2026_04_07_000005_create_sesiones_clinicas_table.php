<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->unsignedBigInteger('plantilla_id')->nullable();
            $table->unsignedBigInteger('especialista_id')->nullable();
            $table->string('titulo');
            $table->date('fecha_sesion');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->enum('estado', ['borrador', 'completada', 'cancelada'])->default('borrador');
            $table->text('observaciones_pre')->nullable();
            $table->text('observaciones_post')->nullable();
            $table->text('productos_usados')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->date('proxima_cita')->nullable();
            $table->text('notas_internas')->nullable();
            $table->string('firma_paciente_path')->nullable();
            $table->timestamp('firmado_en')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('plantilla_id')->references('id')->on('ficha_plantillas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones_clinicas');
    }
};
