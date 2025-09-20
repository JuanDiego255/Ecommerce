<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamp('fecha_inscripcion');
            $table->integer('costo_crc');
            $table->string('ubicacion');
            $table->longText('detalles');
            $table->string('cuenta_sinpe');
            $table->string('cuenta_iban');
            $table->string('imagen_premios')->nullable();
            $table->boolean('activo')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
