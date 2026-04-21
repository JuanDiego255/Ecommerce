<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('space_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('payment_type', ['one_time', 'monthly'])->default('one_time');
            $table->unsignedTinyInteger('time_to_pay')->default(1)->comment('months between payments (monthly only)');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('space_clients');
    }
};
