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
        Schema::create('recipe_tags', function (Blueprint $table) {
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');

            // Single composite primary key - no need for separate ID
            $table->primary(['recipe_id', 'tag_id']);
            
            // Optimized indexes for lookups
            $table->index(['tag_id', 'recipe_id']); // For finding recipes by tag
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_tags');
    }
};
