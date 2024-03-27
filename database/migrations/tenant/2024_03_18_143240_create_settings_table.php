<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('navbar', 100)->nullable();
            $table->string('navbar_text', 100)->nullable();
            $table->string('title_text', 100)->nullable();
            $table->string('btn_cart', 100)->nullable();
            $table->string('btn_cart_text', 100)->nullable();
            $table->string('footer', 100)->nullable();
            $table->string('footer_text', 100)->nullable();
            $table->string('sidebar', 100)->nullable();
            $table->string('sidebar_text', 100)->nullable();
            $table->string('hover', 100)->nullable();
            $table->string('cart_icon', 100)->nullable();
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
        Schema::dropIfExists('settings');
    }
}
