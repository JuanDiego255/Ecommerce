<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('portal_token', 64)->nullable()->unique()->after('activo');
            $table->timestamp('portal_token_expires_at')->nullable()->after('portal_token');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn(['portal_token', 'portal_token_expires_at']);
        });
    }
};
