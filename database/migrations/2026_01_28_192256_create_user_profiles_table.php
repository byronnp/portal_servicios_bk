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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('identification')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('user_name')->unique();
            $table->string('phone')->nullable();
            $table->string('crm_id')->nullable()->unique();
            $table->integer('position')->nullable();
            $table->timestamps();

            $table->index('user_name');
            $table->index('crm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
