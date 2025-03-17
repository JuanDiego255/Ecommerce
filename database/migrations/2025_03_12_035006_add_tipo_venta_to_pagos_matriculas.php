<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoVentaToPagosMatriculas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos_matriculas', function (Blueprint $table) {
            //
            $table->tinyInteger('tipo_venta')->nullable();
            $table->string('detalle',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos_matriculas', function (Blueprint $table) {
            //
        });
    }
}
