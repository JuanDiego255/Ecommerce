<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArqueoIdToMatriculaEstudiantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matricula_estudiantes', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('arqueo_id')->nullable();
            $table->foreign('arqueo_id')->references('id')->on('arqueo_cajas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matricula_estudiantes', function (Blueprint $table) {
            //
        });
    }
}
