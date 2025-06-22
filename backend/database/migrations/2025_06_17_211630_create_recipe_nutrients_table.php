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
        Schema::create('recipe_nutrients', function (Blueprint $table): void {
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('nutrient_id')->constrained('nutrients')->onDelete('cascade');
            $table->decimal('amount', 10, 4); // Nutrient amount (supports precision for small vitamins)
            $table->decimal('percentage_dv', 5, 2)->nullable(); // Percentage daily value (44%, 72%, etc.)

            // Composite primary key - no need for separate ID
            $table->primary(['recipe_id', 'nutrient_id']);

            // Indexes for performance
            $table->index(['recipe_id']); // For getting all nutrients for a recipe
            $table->index(['nutrient_id']); // For finding recipes by nutrient
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_nutrients');
    }
};
