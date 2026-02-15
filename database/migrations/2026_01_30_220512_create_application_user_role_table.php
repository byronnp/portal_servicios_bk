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
        Schema::create('application_user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamp('assigned_at')->nullable()->comment('Fecha de asignación');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null')->comment('Usuario que asignó');
            $table->boolean('is_active')->default(true)->comment('Estado de la asignación');


            // Índices para velocidad de consulta
            $table->index(['user_id', 'application_id']);

            // Evita duplicados exactos (Opcional si permites el mismo rol dos veces)
            $table->unique(['user_id', 'application_id', 'role_id'], 'user_app_role_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_user');
    }
};
