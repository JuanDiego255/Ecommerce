<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('especialistas', function (Blueprint $table) {
            if (!Schema::hasColumn('especialistas', 'aplica_calc')) {
                $table->tinyInteger('aplica_calc')->default(1)->after('monto_por_servicio');
            }
            if (!Schema::hasColumn('especialistas', 'aplica_porc_tarjeta')) {
                $table->tinyInteger('aplica_porc_tarjeta')->default(0)->after('aplica_calc');
            }
            if (!Schema::hasColumn('especialistas', 'aplica_porc_113')) {
                $table->tinyInteger('aplica_porc_113')->default(0)->after('aplica_porc_tarjeta');
            }
            if (!Schema::hasColumn('especialistas', 'aplica_porc_prod')) {
                $table->tinyInteger('aplica_porc_prod')->default(0)->after('aplica_porc_113');
            }
            if (!Schema::hasColumn('especialistas', 'set_campo_esp')) {
                $table->tinyInteger('set_campo_esp')->default(0)->after('aplica_porc_prod');
            }
        });
    }

    public function down(): void
    {
        Schema::table('especialistas', function (Blueprint $table) {
            $table->dropColumn([
                'aplica_calc',
                'aplica_porc_tarjeta',
                'aplica_porc_113',
                'aplica_porc_prod',
                'set_campo_esp',
            ]);
        });
    }
};
