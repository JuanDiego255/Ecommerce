<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstagramPostMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instagram_post_id')->constrained('instagram_posts')->cascadeOnDelete();

            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('media_type', ['image', 'video'])->default('image');
            $table->string('media_path');

            $table->timestamps();
            $table->index(['instagram_post_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_post_media');
    }
}
