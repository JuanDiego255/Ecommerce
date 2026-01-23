<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInstagramCollectionItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instragram_collection_items', function (Blueprint $table) {
            //
            $table->foreignId('group_id')
                ->nullable()
                ->after('instagram_collection_id')
                ->constrained('instagram_collection_groups')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instragram_collection_items', function (Blueprint $table) {
            //
        });
    }
}
