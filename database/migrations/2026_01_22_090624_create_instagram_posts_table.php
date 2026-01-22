<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstagramPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instagram_account_id')->constrained('instagram_accounts')->cascadeOnDelete();

            // si luego ligas al catálogo
            $table->unsignedBigInteger('clothing_id')->nullable();

            $table->enum('type', ['feed', 'story'])->default('feed');
            $table->longText('caption')->nullable();

            $table->enum('status', ['draft', 'scheduled', 'publishing', 'published', 'failed', 'cancelled'])
                ->default('draft');

            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('published_at')->nullable();

            // ids retornados por Meta
            $table->string('meta_container_id')->nullable();
            $table->string('meta_media_id')->nullable();

            $table->longText('error_message')->nullable();

            $table->timestamps();
            $table->index(['status', 'scheduled_at']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_posts');
    }
}
