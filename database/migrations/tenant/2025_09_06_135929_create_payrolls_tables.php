<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_payrolls_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->date('week_start'); // lunes (o el día que definas)
            $table->date('week_end');   // domingo
            $table->string('status')->default('open'); // open|closed|paid
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barbero_id')->constrained()->cascadeOnDelete();

            // fotografía del cálculo (se “congela” al cerrar)
            $table->unsignedInteger('services_count')->default(0);
            $table->bigInteger('gross_cents')->default(0);              // suma total_cents de citas completadas
            $table->decimal('commission_rate', 5, 2);                   // % aplicado (capturado al generar)
            $table->bigInteger('barber_commission_cents')->default(0);  // lo que recibe el barbero
            $table->bigInteger('owner_commission_cents')->default(0);   // lo que queda al owner

            // ajustes manuales opcionales
            $table->bigInteger('adjustment_cents')->default(0)->comment('Ajuste manual +/- al barbero');

            // estado de pago del ítem
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payrolls');
    }
};
