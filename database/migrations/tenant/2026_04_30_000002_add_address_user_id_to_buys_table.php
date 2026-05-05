<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('buys', function (Blueprint $table) {
            if (!Schema::hasColumn('buys', 'address_user_id')) {
                $table->unsignedBigInteger('address_user_id')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('buys', function (Blueprint $table) {
            if (Schema::hasColumn('buys', 'address_user_id')) {
                $table->dropColumn('address_user_id');
            }
        });
    }
};
