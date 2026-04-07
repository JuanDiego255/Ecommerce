<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertas_paciente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->enum('tipo', ['alergia', 'medicamento', 'contraindicacion', 'nota']);
            $table->string('descripcion');
            $table->enum('nivel', ['info', 'warning', 'danger'])->default('warning');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertas_paciente');
    }
};
