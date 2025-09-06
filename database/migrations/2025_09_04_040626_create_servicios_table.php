<?php
// database/migrations/2025_09_03_000001_create_servicios_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('servicios')) {
            Schema::create('servicios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->unsignedSmallInteger('duration_minutes')->default(30);
                $table->unsignedInteger('base_price_cents')->default(0);
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }
    }


    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
