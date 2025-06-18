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
        Schema::create('nutrients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // "Protein", "Vitamin C", "Calories"
            $table->string('slug', 120)->unique(); // "protein", "vitamin-c", "calories"
            $table->string('unit', 20); // "g", "mg", "kcal", "IU", "%"
            $table->string('display_name', 100); // "Protein", "Vitamin C", "Calories"
            $table->enum('category', ['macronutrient', 'vitamin', 'mineral', 'other']);
            $table->text('description')->nullable();
            $table->decimal('daily_value', 8, 2)->nullable(); // RDA/DV for adults
            $table->timestamps();

            // Indexes for performance
            $table->index('category');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrients');
    }
};
