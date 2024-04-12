<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title',50);
            $table->text('title_discount')->nullable();
            $table->text('title_instagram')->nullable();
            $table->text('mision');
            $table->text('title_trend')->nullable();
            $table->string('title_suscrib_a',100);
            $table->string('description_suscrib',100);
            $table->string('footer',100);
            $table->string('logo',100);
            $table->string('login_image',191);
            $table->string('whatsapp',30);
            $table->string('sinpe',30);
            $table->string('email',30)->nullable();
            $table->string('iva',30)->nullable();
            $table->string('delivery',30)->nullable();
            $table->string('tenant',30)->nullable();
            $table->string('count',60)->nullable();
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
        Schema::dropIfExists('tenant_infos');
    }
}
