<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstagramCollectionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('instagram_collection_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instagram_collection_id')
                ->constrained('instagram_collections')
                ->cascadeOnDelete();
            $table->foreignId('instagram_post_id')
                ->nullable()
                ->constrained('instagram_posts')
                ->nullOnDelete();
            $table->string('name')->default('Carrusel');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
