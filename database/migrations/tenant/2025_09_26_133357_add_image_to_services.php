<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $t) {
            $t->string('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $t) {
            $t->dropColumn([
                'image'
            ]);
        });
    }
};
