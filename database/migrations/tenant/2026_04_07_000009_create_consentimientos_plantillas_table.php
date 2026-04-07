<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consentimientos_plantillas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('contenido'); // HTML con variables como {{nombre_paciente}}
            $table->enum('tipo', ['general', 'por_procedimiento'])->default('general');
            $table->boolean('activo')->default(true);
            $table->integer('version')->default(1);
            $table->timestamps();
        });

        Schema::create('consentimientos_firmados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('plantilla_id')->constrained('consentimientos_plantillas')->cascadeOnDelete();
            $table->unsignedBigInteger('sesion_id')->nullable();
            $table->longText('contenido_al_firmar'); // snapshot del contenido en el momento de la firma
            $table->string('firma_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('ip_firma')->nullable();
            $table->timestamp('firmado_en')->nullable();
            $table->timestamps();

            $table->foreign('sesion_id')->references('id')->on('sesiones_clinicas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consentimientos_firmados');
        Schema::dropIfExists('consentimientos_plantillas');
    }
};
