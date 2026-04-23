<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // friendly label, e.g. "App Android Juan"
            $table->string('token', 64)->unique();         // SHA-256 hash of the plain token
            $table->timestamp('last_used_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_tokens');
    }
};
