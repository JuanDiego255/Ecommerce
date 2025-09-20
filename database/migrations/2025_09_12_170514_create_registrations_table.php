<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('event_categories')->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('telefono');
            $table->string('equipo')->nullable();
            $table->string('email');
            $table->string('comprobante_pago'); // ruta en storage
            $table->boolean('terminos')->default(false);
            $table->enum('estado', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
