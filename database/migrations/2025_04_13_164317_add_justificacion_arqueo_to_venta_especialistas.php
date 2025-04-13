<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJustificacionArqueoToVentaEspecialistas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('venta_especialistas', function (Blueprint $table) {
            //
            $table->string('justificacion_arqueo', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venta_especialistas', function (Blueprint $table) {
            //
        });
    }
}
