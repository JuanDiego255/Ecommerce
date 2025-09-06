<?php

// database/migrations/2025_09_04_120000_create_tenant_settings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();                // stancl tenancy key
            $table->unsignedInteger('cancel_window_hours')->default(12);
            $table->unsignedInteger('reschedule_window_hours')->default(6);
            $table->boolean('allow_online_cancel')->default(true);
            $table->boolean('allow_online_reschedule')->default(true);
            $table->unsignedInteger('no_show_fee_cents')->default(0);
            $table->string('email_bcc')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
