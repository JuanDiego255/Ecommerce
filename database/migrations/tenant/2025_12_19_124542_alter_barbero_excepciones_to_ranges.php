<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('barbero_excepciones')) {
            Schema::table('barbero_excepciones', function (Blueprint $table) {
                if (!Schema::hasColumn('barbero_excepciones', 'date_to')) {
                    $table->date('date_to')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('barbero_excepciones')) {
            Schema::table('barbero_excepciones', function (Blueprint $table) {
                if (Schema::hasColumn('barbero_excepciones', 'date_to')) {
                    $table->dropColumn('date_to');
                }
            });
        }
    }
};
