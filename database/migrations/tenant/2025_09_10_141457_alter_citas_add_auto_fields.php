<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('citas')) {
            Schema::table('citas', function (Blueprint $table) {
                if (!Schema::hasColumn('citas', 'client_id')) {
                    $table->foreignId('client_id')->nullable()->after('barbero_id')
                        ->constrained('clients')->nullOnDelete()->index();
                }
                if (!Schema::hasColumn('citas', 'is_auto')) {
                    $table->boolean('is_auto')->default(false)->after('status')->index();
                }
                if (!Schema::hasColumn('citas', 'hold_expires_at')) {
                    $table->timestampTz('hold_expires_at')->nullable()->after('ends_at')->index();
                }

                // Índices útiles para consultas por estado/fecha
                if (!Schema::hasColumn('citas', 'starts_at')) {
                    // Solo por seguridad por si en algún tenant faltara
                    $table->timestampTz('starts_at')->nullable()->index();
                }
                if (!Schema::hasColumn('citas', 'ends_at')) {
                    $table->timestampTz('ends_at')->nullable()->index();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('citas')) {
            Schema::table('citas', function (Blueprint $table) {
                if (Schema::hasColumn('citas', 'hold_expires_at')) {
                    $table->dropColumn('hold_expires_at');
                }
                if (Schema::hasColumn('citas', 'is_auto')) {
                    $table->dropColumn('is_auto');
                }
                if (Schema::hasColumn('citas', 'client_id')) {
                    $table->dropConstrainedForeignId('client_id');
                }
            });
        }
    }
};
