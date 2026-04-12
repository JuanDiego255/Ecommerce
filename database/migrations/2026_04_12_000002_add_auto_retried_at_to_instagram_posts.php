<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instagram_posts', function (Blueprint $table) {
            // Tracks whether this post has already had one automatic retry attempt,
            // preventing infinite re-schedule loops.
            $table->timestamp('auto_retried_at')->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->dropColumn('auto_retried_at');
        });
    }
};
