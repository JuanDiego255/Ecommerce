<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Crear tabla si no existe
        if (!Schema::hasTable('tenant_settings')) {
            Schema::create('tenant_settings', function (Blueprint $table) {
                $table->id();
                // Coloca aquí cualquier campo base que ya uses
                $table->timestamps();
            });
        }

        // Agregar campos si faltan
        Schema::table('tenant_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('tenant_settings', 'auto_book_enabled')) {
                $table->boolean('auto_book_enabled')->default(false);
            }
            if (!Schema::hasColumn('tenant_settings', 'auto_book_min_visits')) {
                $table->unsignedSmallInteger('auto_book_min_visits')->default(3);
            }
            if (!Schema::hasColumn('tenant_settings', 'auto_book_lookback_days')) {
                $table->unsignedSmallInteger('auto_book_lookback_days')->default(90);
            }
            if (!Schema::hasColumn('tenant_settings', 'auto_book_confirm_hold_hours')) {
                $table->unsignedSmallInteger('auto_book_confirm_hold_hours')->default(36);
            }
            if (!Schema::hasColumn('tenant_settings', 'auto_book_default_cadence_days')) {
                $table->unsignedSmallInteger('auto_book_default_cadence_days')->default(30);
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('tenant_settings')) {
            Schema::table('tenant_settings', function (Blueprint $table) {
                foreach (
                    [
                        'auto_book_enabled',
                        'auto_book_min_visits',
                        'auto_book_lookback_days',
                        'auto_book_confirm_hold_hours',
                        'auto_book_default_cadence_days',
                    ] as $col
                ) {
                    if (Schema::hasColumn('tenant_settings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
