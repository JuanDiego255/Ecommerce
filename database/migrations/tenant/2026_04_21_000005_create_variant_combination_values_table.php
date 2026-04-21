<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('variant_combination_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combination_id')->constrained('variant_combinations')->onDelete('cascade');
            $table->foreignId('attr_id')->constrained('attributes')->onDelete('cascade');
            $table->foreignId('value_attr')->constrained('attribute_values')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variant_combination_values');
    }
};
