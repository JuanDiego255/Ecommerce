<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_especialistas', function (Blueprint $table) {
            // Acelera el DataTable de ventas ordenado por created_at desc
            if (!$this->indexExists('venta_especialistas', 'venta_esp_created_at_idx')) {
                $table->index('created_at', 'venta_esp_created_at_idx');
            }
            // Acelera filtros por especialista en reportes
            if (!$this->indexExists('venta_especialistas', 'venta_esp_especialista_estado_idx')) {
                $table->index(['especialista_id', 'estado'], 'venta_esp_especialista_estado_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('venta_especialistas', function (Blueprint $table) {
            $table->dropIndex('venta_esp_created_at_idx');
            $table->dropIndex('venta_esp_especialista_estado_idx');
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = \Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
            [$indexName]
        );
        return count($indexes) > 0;
    }
};
