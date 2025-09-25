<?php

// database/migrations/2025_09_25_000000_add_auto_recurring_to_clients.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $t) {
            // opt-in ya existe
            $t->enum('auto_book_frequency', ['weekly', 'biweekly'])->nullable()->after('auto_book_opt_in');         
            $t->unsignedSmallInteger('auto_book_lookahead_days')->nullable()->after('last_auto_booked_at');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $t) {
            $t->dropColumn([
                'auto_book_frequency',                
                'auto_book_lookahead_days',
            ]);
        });
    }
};
