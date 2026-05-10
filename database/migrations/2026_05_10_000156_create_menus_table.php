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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->foreignId('permission_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('label');
            $table->string('route_name')->nullable();
            $table->string('path')->nullable();
            $table->text('external_url')->nullable();
            $table->text('icon')->nullable();
            $table->string('component')->nullable();
            $table->unsignedSmallInteger('depth')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('opens_new_tab')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['application_id', 'name']);
            $table->index(['application_id', 'parent_id']);
            $table->index(['application_id', 'is_active', 'is_visible']);
            $table->index(['parent_id', 'sort_order']);
            $table->index('permission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
