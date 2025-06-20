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
        Schema::create('recipe_tricks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->text('content'); // The trick/note content
            $table->enum('type', ['note', 'tip', 'trick', 'warning'])->default('trick'); // Type of content
            $table->integer('sort')->default(0); // Display order (from JSON array order)
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipe_id', 'sort']); // Common query pattern for ordered display
            $table->index(['recipe_id', 'type']); // Filter by content type
            $table->index('recipe_id'); // Basic recipe lookup
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_tricks');
    }
};
