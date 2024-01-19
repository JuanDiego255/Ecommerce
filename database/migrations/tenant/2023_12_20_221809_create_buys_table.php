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
            $table->string('name',50)->nullable();
            $table->string('email',100)->nullable();
            $table->string('telephone',50)->nullable();
            $table->string('address',191)->nullable();
            $table->string('address_two',191)->nullable();
            $table->string('city',60)->nullable();
            $table->string('province',60)->nullable();
            $table->string('country',80)->nullable();
            $table->string('postal_code',50)->nullable();           
            $table->string('total_iva',60); 
            $table->string('total_buy',60); 
            $table->tinyInteger('delivered',2);  
            $table->tinyInteger('approved',2);   
            $table->tinyInteger('cancel_buy',2)->nullable();
            $table->string('total_delivery',60)->nullable();
            $table->string('image',191);                           
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
