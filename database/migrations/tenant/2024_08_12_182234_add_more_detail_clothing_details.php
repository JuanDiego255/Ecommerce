<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreDetailClothingDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clothing_details', function (Blueprint $table) {
            //
            $table->string('color')->nullable();
            $table->string('modelo')->nullable();
            $table->string('kilometraje')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clothing_details', function (Blueprint $table) {
            //
        });
    }
}
