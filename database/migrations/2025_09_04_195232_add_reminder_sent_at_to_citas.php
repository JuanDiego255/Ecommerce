<?php

// database/migrations/2025_09_04_000000_add_reminder_sent_at_to_citas.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (!Schema::hasColumn('citas', 'reminder_sent_at')) {
                $table->dateTime('reminder_sent_at')->nullable()->after('status');
            }
        });
    }
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (Schema::hasColumn('citas', 'reminder_sent_at')) {
                $table->dropColumn('reminder_sent_at');
            }
        });
    }
};
