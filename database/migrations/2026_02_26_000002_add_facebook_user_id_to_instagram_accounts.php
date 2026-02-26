<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instagram_accounts', function (Blueprint $table) {
            // ID del usuario de Facebook que autorizó la app.
            // Necesario para que el Data Deletion Callback sepa exactamente
            // qué cuenta borrar cuando Meta envía un user_id.
            $table->string('facebook_user_id', 64)->nullable()->index()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('instagram_accounts', function (Blueprint $table) {
            $table->dropColumn('facebook_user_id');
        });
    }
};
