<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('total_iva'); 
            $table->string('total_buy'); 
            $table->tinyInteger('delivered');  
            $table->tinyInteger('approved');   
            $table->string('image');                           
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
        Schema::dropIfExists('buys');
    }
}
