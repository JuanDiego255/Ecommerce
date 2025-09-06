<?php
// database/migrations/2025_09_03_000003_create_citas_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('barberos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cliente_nombre')->nullable();
            $table->string('cliente_email')->nullable();
            $table->string('cliente_telefono')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedInteger('total_cents');
            $table->string('status')->default('pending');
            $table->text('notas')->nullable();
            $table->string('source')->default('landing');
            $table->timestamps();
            $table->index(['barbero_id', 'starts_at']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
