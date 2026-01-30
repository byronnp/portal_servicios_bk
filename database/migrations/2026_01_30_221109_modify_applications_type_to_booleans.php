<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Eliminar columna type
            $table->dropColumn('type');

            // Agregar columnas booleanas
            $table->boolean('is_web')->default(false)->comment('Aplicación disponible para web')->after('token');
            $table->boolean('is_mobile')->default(false)->comment('Aplicación disponible para mobile')->after('is_web');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Eliminar columnas booleanas
            $table->dropColumn(['is_web', 'is_mobile']);

            // Restaurar columna type
            $table->enum('type', ['web', 'mobile'])->default('web')->comment('Tipo de aplicación')->after('token');
        });
    }
};
