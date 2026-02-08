<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQueueSettingsToInstagramCaptionSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_caption_settings', function (Blueprint $table) {
            $table->integer('queue_interval_hours')->default(4)->after('max_hashtags');
            $table->string('queue_start_hour', 5)->default('09:00')->after('queue_interval_hours');
            $table->string('queue_end_hour', 5)->default('21:00')->after('queue_start_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instagram_caption_settings', function (Blueprint $table) {
            $table->dropColumn(['queue_interval_hours', 'queue_start_hour', 'queue_end_hour']);
        });
    }
}
