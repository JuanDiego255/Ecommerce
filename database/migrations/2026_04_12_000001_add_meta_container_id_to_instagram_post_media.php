<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instagram_post_media', function (Blueprint $table) {
            // Stores the Instagram container ID for this media item so that
            // retries can reuse already-created containers instead of creating
            // orphan containers that count against the API rate limits.
            $table->string('meta_container_id')->nullable()->after('media_path');
        });
    }

    public function down(): void
    {
        Schema::table('instagram_post_media', function (Blueprint $table) {
            $table->dropColumn('meta_container_id');
        });
    }
};
