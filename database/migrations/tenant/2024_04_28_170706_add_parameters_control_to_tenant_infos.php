<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParametersControlToTenantInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenant_infos', function (Blueprint $table) {
            //
            $table->tinyInteger('show_stock')->default(1);
            $table->tinyInteger('show_insta')->default(1);
            $table->tinyInteger('show_trending')->default(1);
            $table->tinyInteger('show_cintillo')->default(1);
            $table->tinyInteger('show_mision')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenant_infos', function (Blueprint $table) {
            //
        });
    }
}
