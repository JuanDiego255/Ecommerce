<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeValueCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_value_cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id')->nullable();            
            $table->unsignedBigInteger('attr_id')->nullable();
            $table->unsignedBigInteger('value_attr')->nullable();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('attr_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('value_attr')->references('id')->on('attribute_values')->onDelete('cascade');
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
        Schema::dropIfExists('attribute_value_cars');
    }
}
