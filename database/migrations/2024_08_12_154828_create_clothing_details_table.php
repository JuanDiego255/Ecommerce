<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClothingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clothing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clothing_id');
            $table->string('distancia_suelo')->nullable();
            $table->string('peso')->nullable();
            $table->string('capacidad_tanque')->nullable();
            $table->string('combustible')->nullable();
            $table->string('motor')->nullable();
            $table->string('potencia')->nullable();
            $table->string('pasajeros')->nullable();
            $table->string('llantas')->nullable();
            $table->string('traccion')->nullable();
            $table->string('transmision')->nullable();
            $table->string('largo')->nullable();
            $table->string('ancho')->nullable();
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
        Schema::dropIfExists('clothing_details');
    }
}
