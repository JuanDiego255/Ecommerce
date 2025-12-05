<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoulettePrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roulette_prizes', function (Blueprint $table) {
            $table->id();
            $table->string('label');              // Texto que se verá en la ruleta: "5% OFF"
            $table->integer('discount_percent');  // 0 si no hay descuento
            $table->integer('weight')->default(1)->nullable(); // Peso para probabilidades
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('roulette_prizes');
    }
}
