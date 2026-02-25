<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('servicios', 'category_service_id')) {
            Schema::table('servicios', function (Blueprint $table) {
                $table->unsignedBigInteger('category_service_id')->nullable()->after('image');
                $table->foreign('category_service_id')
                    ->references('id')
                    ->on('category_services')
                    ->nullOnDelete();
            });
        }

        // Create "General" category if it doesn't exist, then assign all uncategorized services to it
        $categoryId = DB::table('category_services')->where('nombre', 'General')->value('id');

        if (!$categoryId) {
            $categoryId = DB::table('category_services')->insertGetId([
                'nombre'      => 'General',
                'descripcion' => 'Categoría general de servicios',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        DB::table('servicios')
            ->whereNull('category_service_id')
            ->update(['category_service_id' => $categoryId]);
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropForeign(['category_service_id']);
            $table->dropColumn('category_service_id');
        });
    }
};
