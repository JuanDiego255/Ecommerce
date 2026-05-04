<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('buy_details')) return;
        if (Schema::hasColumn('buy_details', 'combination_id')) return;

        Schema::table('buy_details', function (Blueprint $table) {
            $table->unsignedBigInteger('combination_id')->nullable()->after('clothing_id');
            $table->index('combination_id', 'bd_combination_id_idx');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('buy_details')) return;
        if (!Schema::hasColumn('buy_details', 'combination_id')) return;

        Schema::table('buy_details', function (Blueprint $table) {
            $table->dropIndex('bd_combination_id_idx');
            $table->dropColumn('combination_id');
        });
    }
};
