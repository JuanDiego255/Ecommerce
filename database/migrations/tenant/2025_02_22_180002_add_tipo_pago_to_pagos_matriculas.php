<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoPagoToPagosMatriculas extends Migration
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
            $table->unsignedBigInteger('tipo_pago_id')->nullable();
            $table->foreign('tipo_pago_id')->references('id')->on('tipo_pagos')->onDelete('cascade');
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
