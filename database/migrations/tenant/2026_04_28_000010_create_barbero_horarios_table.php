<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('barbero_horarios')) {
            return;
        }

        Schema::create('barbero_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('barberos')->cascadeOnDelete();
            $table->json('dias');         // e.g. [1,2,3,4,5]  (0=Dom … 6=Sáb)
            $table->time('hora_inicio');  // '09:00:00'
            $table->time('hora_fin');     // '18:00:00'
            $table->timestamps();

            $table->index('barbero_id', 'bh_barbero_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barbero_horarios');
    }
};
