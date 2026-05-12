<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('clients', 'auto_book_price')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->unsignedInteger('auto_book_price')->nullable()->after('auto_book_lookahead_days')
                    ->comment('Precio fijo en colones para citas auto-agendadas. Se multiplica x100 para total_cents.');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('clients', 'auto_book_price')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('auto_book_price');
            });
        }
    }
};
