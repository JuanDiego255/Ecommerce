<?php

// database/migrations/2025_09_04_000300_add_schedule_fields_to_barberos.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            if (!Schema::hasColumn('barberos', 'slot_minutes')) {
                $table->unsignedSmallInteger('slot_minutes')->default(30)->after('monto_por_servicio');
            }
            if (!Schema::hasColumn('barberos', 'work_start')) {
                $table->time('work_start')->default('09:00:00');
            }
            if (!Schema::hasColumn('barberos', 'work_end')) {
                $table->time('work_end')->default('18:00:00');
            }
            if (!Schema::hasColumn('barberos', 'work_days')) {
                // array de ints 0..6 (dom..sab). Por defecto Lun-Vie (1..5)
                $table->json('work_days')->nullable()->default(json_encode([1, 2, 3, 4, 5]));
            }
            if (!Schema::hasColumn('barberos', 'buffer_minutes')) {
                $table->unsignedSmallInteger('buffer_minutes')->default(0);
            }
        });
    }
    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            foreach (['slot_minutes', 'work_start', 'work_end', 'work_days', 'buffer_minutes'] as $col) {
                if (Schema::hasColumn('barberos', $col)) $table->dropColumn($col);
            }
        });
    }
};
