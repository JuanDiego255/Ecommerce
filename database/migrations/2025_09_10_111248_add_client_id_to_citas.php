<?php

// database/migrations/2025_09_08_000001_add_client_id_to_citas.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $t) {
            $t->unsignedBigInteger('client_id')->nullable()->index()->after('id');
            // Si quieres FK estricta:
            // $t->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
        });
    }
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $t) {
            $t->dropColumn('client_id');
        });
    }
};
