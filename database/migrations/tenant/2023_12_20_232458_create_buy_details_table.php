<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buy_id'); 
            $table->unsignedBigInteger('clothing_id');
            $table->string('total',80); 
            $table->string('iva',80);      
            $table->string('quantity',80);       
            $table->tinyInteger('cancel_item')->nullable();
            $table->string('total_delivery',60)->nullable();
            $table->foreign('buy_id')->references('id')->on('buys')->onDelete('cascade');
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
        Schema::dropIfExists('buy_details');
    }
}
