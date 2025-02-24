<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaEspecialistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta_especialistas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('especialista_id')->nullable();
            $table->unsignedBigInteger('arqueo_id')->nullable();
            $table->unsignedBigInteger('clothing_id')->nullable();
            $table->decimal('monto_venta', 28, 2)->nullable();
            $table->decimal('porcentaje', 28, 2)->nullable();
            $table->decimal('descuento', 28, 2)->nullable();
            $table->decimal('monto_producto_venta', 28, 2)->nullable();
            $table->decimal('monto_por_servicio_o_salario', 28, 2)->nullable();
            $table->decimal('monto_clinica', 28, 2)->nullable();
            $table->decimal('monto_especialista', 28, 2)->nullable();
            $table->foreign('especialista_id')->references('id')->on('especialistas')->onDelete('cascade');
            $table->foreign('arqueo_id')->references('id')->on('arqueo_cajas')->onDelete('cascade');
            $table->foreign('clothing_id')->references('id')->on('clothing')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venta_especialistas');
    }
}
