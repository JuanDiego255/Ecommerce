<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            if (!Schema::hasColumn('barberos', 'salario_base')) {
                $table->unsignedInteger('salario_base')->default(0)->after('telefono'); // en colones
            }
            if (!Schema::hasColumn('barberos', 'monto_por_servicio')) {
                $table->unsignedInteger('monto_por_servicio')->default(0)->after('salario_base'); // en colones
            }
        });
    }

    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            if (Schema::hasColumn('barberos', 'monto_por_servicio')) {
                $table->dropColumn('monto_por_servicio');
            }
            if (Schema::hasColumn('barberos', 'salario_base')) {
                $table->dropColumn('salario_base');
            }
        });
    }
};
