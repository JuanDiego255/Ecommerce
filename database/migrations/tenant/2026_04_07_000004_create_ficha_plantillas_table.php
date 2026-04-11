<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ficha_plantillas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('categoria')->nullable(); // limpieza, coloracion, corporal, valoracion, etc.
            $table->string('icono')->nullable();      // material icon name
            $table->string('color_etiqueta')->default('#5e72e4'); // hex color for pill
            $table->json('campos')->nullable();       // full form structure JSON
            $table->integer('version')->default(1);
            $table->boolean('activa')->default(true);
            $table->boolean('es_sistema')->default(false); // plantillas predefinidas
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ficha_plantillas');
    }
};
