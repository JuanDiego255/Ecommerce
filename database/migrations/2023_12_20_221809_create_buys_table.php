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
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('session_id')->nullable();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('address')->nullable();
            $table->string('address_two')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();           
            $table->string('total_iva'); 
            $table->string('total_buy'); 
            $table->tinyInteger('delivered');  
            $table->tinyInteger('approved'); 
            $table->string('kind_of_buy',2)->nullable();
            $table->string('detail',191)->nullable();   
            $table->tinyInteger('cancel_buy')->nullable();
            $table->string('image')->nullable();                           
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
