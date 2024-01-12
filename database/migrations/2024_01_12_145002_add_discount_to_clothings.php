<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToClothings extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clothing', function (Blueprint $table) {
            // if not exist, add the new column
            if (!Schema::hasColumn('clothing', 'discount')) {
                $table->string('discount',30)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clothing', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
}
