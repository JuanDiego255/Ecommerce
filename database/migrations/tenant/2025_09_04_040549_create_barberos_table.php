<?php
// database/migrations/2025_09_03_000000_create_barberos_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('barberos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            // Agenda
            $table->unsignedSmallInteger('slot_minutes')->default(30);
            $table->time('work_start')->default('09:00:00');
            $table->time('work_end')->default('18:00:00');
            $table->json('work_days')->nullable(); // [1,2,3,4,5]
            $table->boolean('activo')->default(true);
            // relación con usuario dueño (opcional)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();


            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('barberos');
    }
};
