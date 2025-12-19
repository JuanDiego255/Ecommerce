<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barbero_excepciones', function (Blueprint $table) {
            // Índice para búsquedas por rango
            $table->index(['barbero_id', 'date', 'date_to'], 'bx_barbero_excepciones_rango');
        });

        // Inicializar date_to = date para registros existentes
        DB::table('barbero_excepciones')->update([
            'date_to' => DB::raw('`date`')
        ]);
    }

    public function down(): void
    {
        Schema::table('barbero_excepciones', function (Blueprint $table) {
            $table->dropIndex('bx_barbero_excepciones_rango');
            $table->dropColumn('date_to');
        });
    }
};
