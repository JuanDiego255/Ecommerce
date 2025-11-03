<?php

// database/migrations/2025_10_27_000000_create_admin_device_tokens_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('admin_device_tokens', function (Blueprint $table) {
            $table->id();

            // quién es
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // a qué tenant pertenece este admin (ajusta el tipo de dato que uses para tenant)
            $table->string('tenant', 191)->nullable()->index();

            // token único que nos da FCM
            $table->string('token', 500)->unique();

            // por curiosidad/debug
            $table->string('platform', 50)->nullable(); // 'web', 'android', 'ios'

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('admin_device_tokens');
    }
};
