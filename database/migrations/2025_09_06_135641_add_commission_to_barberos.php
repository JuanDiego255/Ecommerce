<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_commission_to_barberos.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->nullable()
                ->comment('Porcentaje de comisión para el barbero (0-100), null => usa default');
        });
    }
    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->dropColumn('commission_rate');
        });
    }
};
