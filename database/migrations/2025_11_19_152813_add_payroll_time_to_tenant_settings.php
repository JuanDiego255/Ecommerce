<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tenant_settings')) {
            Schema::table('tenant_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('tenant_settings', 'payroll_time')) {
                    $table->tinyInteger('payroll_time')->default(7)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tenant_settings')) {
            Schema::table('tenant_settings', function (Blueprint $table) {
                if (Schema::hasColumn('tenant_settings', 'payroll_time')) {
                    $table->dropColumn('payroll_time');
                }
            });
        }
    }
};
