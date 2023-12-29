<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelItemToBuyDetails extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_details', function (Blueprint $table) {
            // if not exist, add the new column
            if (!Schema::hasColumn('buys', 'cancel_item')) {
                $table->tinyInteger('cancel_item')->nullable();
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
        Schema::table('buy_details', function (Blueprint $table) {
            $table->dropColumn('cancel_item');
        });
    }
}
