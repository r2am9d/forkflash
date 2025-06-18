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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('category', 50)->index(); // dietary, cooking-method, flavor, occasion, etc.
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color for UI display
            $table->boolean('is_dietary_restriction')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('usage_count')->default(0); // Track popularity
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['category', 'is_featured']);
            $table->index(['is_dietary_restriction']);
            $table->index(['usage_count']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
