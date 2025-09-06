<?php

// database/migrations/2025_09_05_000001_add_photo_to_barberos.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('nombre');
        });
    }
    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
