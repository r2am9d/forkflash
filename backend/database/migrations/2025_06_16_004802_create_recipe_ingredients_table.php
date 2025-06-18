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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->decimal('quantity', 8, 2)->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->string('preparation_notes', 255)->nullable()->comment('diced, chopped, etc.');
            $table->boolean('is_optional')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipe_id', 'display_order']);
            $table->index(['ingredient_id']);
            $table->index(['unit_id']);
            
            // Prevent duplicate ingredient entries per recipe
            $table->unique(['recipe_id', 'ingredient_id', 'display_order']);
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
