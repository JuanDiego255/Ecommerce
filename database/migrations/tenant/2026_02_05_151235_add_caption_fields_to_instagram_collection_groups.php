<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaptionFieldsToInstagramCollectionGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_collection_groups', function (Blueprint $table) {
            $table->text('generated_caption')->nullable()->after('sort_order');
            $table->string('caption_type', 20)->nullable()->after('generated_caption'); // template, instagram, ecommerce
            $table->boolean('use_template')->default(false)->after('caption_type');
            $table->boolean('analyze_images')->default(false)->after('use_template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instagram_collection_groups', function (Blueprint $table) {
            $table->dropColumn(['generated_caption', 'caption_type', 'use_template', 'analyze_images']);
        });
    }
}
