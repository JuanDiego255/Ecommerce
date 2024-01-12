<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalDeliveryToBuys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buys', function (Blueprint $table) {
            // if not exist, add the new column
            if (!Schema::hasColumn('clothing', 'total_delivery')) {
                $table->string('total_delivery',30)->nullable();
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
        Schema::table('buys', function (Blueprint $table) {
            $table->dropColumn('total_delivery');
        });
    }
}
