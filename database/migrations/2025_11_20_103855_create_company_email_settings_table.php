<?php

// database/migrations/2025_01_01_000000_create_company_email_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEmailSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('company_email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('mailer')->default('smtp');
            $table->string('host');
            $table->integer('port')->default(587);
            $table->string('username');
            $table->text('password');
            $table->string('encryption')->default('tls')->nullable(); // tls, ssl, null
            $table->string('from_address');
            $table->string('from_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_email_settings');
    }
}
