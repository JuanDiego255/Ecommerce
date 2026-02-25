<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('barberos', 'is_general')) {
            Schema::table('barberos', function (Blueprint $table) {
                $table->boolean('is_general')->default(false)->after('activo');
            });
        }
    }

    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->dropColumn('is_general');
        });
    }
};
