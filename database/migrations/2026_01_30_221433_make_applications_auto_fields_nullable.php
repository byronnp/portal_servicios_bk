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
            $table->string('slug')->nullable()->change();
            $table->string('hash')->nullable()->change();
            $table->string('token')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->string('hash')->nullable(false)->change();
            $table->string('token')->nullable(false)->change();
        });
    }
};
