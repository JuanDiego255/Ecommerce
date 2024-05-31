<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateButtonIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('button_icons', function (Blueprint $table) {
            $table->id();
            $table->string('home')->default('home')->nullable();
            $table->string('categories')->default('home')->nullable();
            $table->string('cart')->default('shopping-cart')->nullable();
            $table->string('shopping')->default('credit-card')->nullable();
            $table->string('address')->default('map-marker')->nullable();
            $table->string('user')->default('user')->nullable();
            $table->string('services')->default('home')->nullable();
            $table->string('products')->default('home')->nullable();
            $table->string('detail')->default('home')->nullable();
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
        Schema::dropIfExists('button_icons');
    }
}
