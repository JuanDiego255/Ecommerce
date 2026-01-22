<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstagramAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_accounts', function (Blueprint $table) {
            $table->id();

            // dueño (admin/usuario en tu ecommerce)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // Datos Meta/IG
            $table->string('facebook_page_id')->nullable();
            $table->text('facebook_page_access_token')->nullable();
            $table->string('instagram_business_account_id')->nullable(); // ig_user_id
            $table->string('instagram_username')->nullable();
            // business|creator (si logras detectarlo)
            $table->string('account_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('token_expires_at')->nullable();
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
        Schema::dropIfExists('instagram_accounts');
    }
}
