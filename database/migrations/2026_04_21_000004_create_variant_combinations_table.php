<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('variant_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clothing_id')->constrained('clothing')->onDelete('cascade');
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->tinyInteger('manage_stock')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variant_combinations');
    }
};
