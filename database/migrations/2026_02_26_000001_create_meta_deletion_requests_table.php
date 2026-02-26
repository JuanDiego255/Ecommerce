<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meta_deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->string('confirmation_code', 64)->unique();
            $table->string('facebook_user_id', 64)->nullable()->index();
            $table->string('status', 20)->default('pending'); // pending | completed | failed
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meta_deletion_requests');
    }
};
