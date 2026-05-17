<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variant_combinations', function (Blueprint $table) {
            $table->tinyInteger('override_base')->default(0)->after('manage_stock');
        });
    }

    public function down(): void
    {
        Schema::table('variant_combinations', function (Blueprint $table) {
            $table->dropColumn('override_base');
        });
    }
};
