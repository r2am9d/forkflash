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
        Schema::create('recipe_tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->text('tip_text'); // The actual tip content
            $table->enum('tip_category', ['cooking', 'prep', 'substitution', 'storage'])->default('cooking'); // Categorize tips
            $table->integer('display_order')->default(0); // Order to display tips
            $table->boolean('is_public')->default(true); // Allow private tips in the future
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Track who added the tip
            $table->integer('helpfulness_score')->default(0); // Community rating of tip usefulness
            $table->integer('times_used')->default(0); // Track how often this tip is referenced
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipe_id', 'display_order']); // Common query pattern
            $table->index(['tip_category']); // Filter by tip category
            $table->index(['recipe_id', 'tip_category']); // Filter tips by category for a recipe
            $table->index(['created_by_user_id']); // User's tips
            $table->index(['helpfulness_score']); // Find most helpful tips
            $table->index(['times_used']); // Find most popular tips
            
            // Basic recipe lookup
            $table->index(['recipe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_tips');
    }
};
