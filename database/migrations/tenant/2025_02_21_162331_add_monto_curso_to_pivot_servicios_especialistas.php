<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMontoCursoToPivotServiciosEspecialistas extends Migration
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
            $table->decimal('monto_curso', 28, 2)->nullable();
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
