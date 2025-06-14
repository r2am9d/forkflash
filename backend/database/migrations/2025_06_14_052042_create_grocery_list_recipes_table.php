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
        Schema::create('grocery_list_recipes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('grocery_list_id')->constrained('grocery_lists', 'id')->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained('recipes', 'id')->onDelete('cascade');
            $table->integer('servings')->default(1); // How many servings of this recipe to shop for
            $table->json('selected_item_ids')->nullable(); // Which ingredients were selected from recipe
            $table->boolean('auto_generated')->default(true); // Whether items were auto-generated
            $table->timestamps();

            // Indexes for performance
            $table->index(['grocery_list_id', 'recipe_id']);
            $table->index('grocery_list_id');
            $table->index('recipe_id');

            // Ensure unique combination
            $table->unique(['grocery_list_id', 'recipe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grocery_list_recipes');
    }
};
