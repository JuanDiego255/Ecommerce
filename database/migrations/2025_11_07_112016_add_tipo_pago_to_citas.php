<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('citas')) {
            Schema::table('citas', function (Blueprint $table) {
                if (!Schema::hasColumn('citas', 'payment_type')) {
                    $table->string('payment_type')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('citas')) {
            Schema::table('citas', function (Blueprint $table) {
                if (Schema::hasColumn('citas', 'payment_type')) {
                    $table->dropColumn('payment_type');
                }
            });
        }
    }
};
