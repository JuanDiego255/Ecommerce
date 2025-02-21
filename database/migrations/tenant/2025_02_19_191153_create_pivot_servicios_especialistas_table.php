<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotServiciosEspecialistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_servicios_especialistas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('especialista_id')->nullable();
            $table->unsignedBigInteger('clothing_id')->nullable();
            $table->foreign('especialista_id')->references('id')->on('especialistas')->onDelete('cascade');
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
        Schema::dropIfExists('pivot_servicios_especialistas');
    }
}
