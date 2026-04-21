<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('combination_id')->nullable()->after('clothing_id');
            $table->foreign('combination_id')->references('id')->on('variant_combinations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['combination_id']);
            $table->dropColumn('combination_id');
        });
    }
};
