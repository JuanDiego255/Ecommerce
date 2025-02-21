<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArqueoCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arqueo_cajas', function (Blueprint $table) {
            $table->id();                      
            $table->date('fecha_ini');
            $table->date('fecha_fin')->nullable();
            $table->string('total_ventas',40)->nullable();
            $table->tinyInteger('estado')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('caja_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');           
            $table->foreign('caja_id')->references('id')->on('cajas')->onDelete('cascade');
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
        Schema::dropIfExists('arqueo_cajas');
    }
}
