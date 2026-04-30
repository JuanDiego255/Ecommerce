<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('barbero_descansos')) {
            return;
        }

        Schema::create('barbero_descansos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('barberos')->cascadeOnDelete();
            $table->json('dias');         // [0,1,2,3,4,5,6] — días que aplica
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('motivo', 100)->nullable(); // "Almuerzo", "Reunión", …
            $table->timestamps();

            $table->index('barbero_id', 'bd_barbero_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barbero_descansos');
    }
};
