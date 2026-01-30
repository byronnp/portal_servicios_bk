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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('hash')->unique()->comment('Hash único de la aplicación');
            $table->string('token')->unique()->comment('Token de acceso de la aplicación');
            $table->enum('type', ['web', 'mobile'])->default('web')->comment('Tipo de aplicación');
            $table->string('start_url')->nullable()->comment('URL de inicio de la aplicación');
            $table->string('icon')->nullable()->comment('Icono de la aplicación');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
