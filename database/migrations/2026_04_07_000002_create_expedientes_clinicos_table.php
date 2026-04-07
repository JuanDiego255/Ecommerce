<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expedientes_clinicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->string('numero_expediente')->unique();
            $table->date('fecha_apertura');
            $table->date('ultima_visita')->nullable();
            // Antecedentes médicos
            $table->text('alergias')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->text('condiciones_medicas')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('antecedentes_esteticos')->nullable();
            // Condiciones relevantes para estética
            $table->boolean('embarazo')->default(false);
            $table->boolean('lactancia')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('hipertension')->default(false);
            $table->boolean('epilepsia')->default(false);
            $table->boolean('problemas_coagulacion')->default(false);
            $table->boolean('piel_sensible')->default(false);
            $table->boolean('queloides')->default(false);
            $table->boolean('rosacea')->default(false);
            $table->boolean('fuma')->default(false);
            $table->boolean('consume_alcohol')->default(false);
            // Notas
            $table->text('observaciones_generales')->nullable();
            $table->boolean('consentimiento_general_firmado')->default(false);
            $table->timestamp('consentimiento_fecha')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expedientes_clinicos');
    }
};
