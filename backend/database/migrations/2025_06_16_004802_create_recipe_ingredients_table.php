<?php

declare(strict_types=1);

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
        Schema::create('recipe_ingredients', function (Blueprint $table): void {
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->text('display_text')->comment('Original ingredient text');
            $table->integer('sort')->default(0);

            // Composite primary key - no need for separate ID
            $table->primary(['recipe_id', 'ingredient_id']);

            // Optimized indexes for lookups
            $table->index(['recipe_id', 'sort']); // For ordered ingredient lists
            $table->index(['ingredient_id']); // For finding recipes by ingredient
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
