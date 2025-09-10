<?php

// database/migrations/2025_09_08_000002_add_auto_book_rules_to_tenant_settings.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tenant_settings', function (Blueprint $t) {
            $t->boolean('auto_book_enabled')->default(true);
            $t->unsignedInteger('auto_book_min_visits')->default(3);
            $t->unsignedInteger('auto_book_lookback_days')->default(90);
            $t->unsignedInteger('auto_book_confirm_hold_hours')->default(36);
        });
    }
    public function down(): void
    {
        Schema::table('tenant_settings', function (Blueprint $t) {
            $t->dropColumn([
                'auto_book_enabled',
                'auto_book_min_visits',
                'auto_book_lookback_days',
                'auto_book_confirm_hold_hours'
            ]);
        });
    }
};
