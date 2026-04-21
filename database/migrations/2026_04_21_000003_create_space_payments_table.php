<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('space_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('space_clients')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('description', 255)->nullable();
            $table->string('payment_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('space_payments');
    }
};
