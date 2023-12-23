<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeClothsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('size_cloths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('size_id');
            $table->unsignedBigInteger('clothing_id');           
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
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
        Schema::dropIfExists('size_cloths');
    }
}
