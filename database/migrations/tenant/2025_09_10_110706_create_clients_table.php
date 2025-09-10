<?php

// database/migrations/2025_09_08_000000_create_clients_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $t) {
            $t->id();
            $t->string('nombre')->nullable();
            $t->string('email')->nullable()->index();
            $t->string('telefono')->nullable();
            $t->boolean('auto_book_opt_in')->default(false);
            $t->unsignedBigInteger('preferred_barbero_id')->nullable()->index();
            $t->json('preferred_days')->nullable();     // ej [1,3,5] (L=1..D=0 si usas Carbon)
            $t->time('preferred_start')->nullable();    // ej 10:00
            $t->time('preferred_end')->nullable();      // ej 12:00
            $t->timestamp('last_seen_at')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
