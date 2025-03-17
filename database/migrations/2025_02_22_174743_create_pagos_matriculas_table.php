<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_matriculas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matricula_id')->nullable();
            $table->unsignedBigInteger('arqueo_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('monto_pago', 28, 2)->nullable();
            $table->decimal('descuento', 28, 2)->nullable();
            $table->date('fecha_pago');
            $table->foreign('matricula_id')->references('id')->on('matricula_estudiantes')->onDelete('cascade');
            $table->foreign('arqueo_id')->references('id')->on('arqueo_cajas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('pagos_matriculas');
    }
}
