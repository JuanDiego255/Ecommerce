<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('clothing_id');
            $table->unsignedBigInteger('size_id');
            $table->string('quantity');
            $table->tinyInteger('sold');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('clothing_id')->references('id')->on('clothing')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
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
        Schema::dropIfExists('carts');
    }
}
