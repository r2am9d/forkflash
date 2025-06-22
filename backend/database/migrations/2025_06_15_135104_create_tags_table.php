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
            $table->string('color', 7)->default('#6B7280'); // Hex color for UI display
            $table->integer('usage_count')->default(0)->unsigned(); // Track popularity for suggestions
            $table->timestamps();

            // Simple performance indexes
            $table->index(['usage_count']); // For popular tag suggestions
            $table->index(['name']); // For tag search/autocomplete
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
