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
            $table->integer('sort')->default(0); // Order to display tips (from JSON array order)
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipe_id', 'sort']); // Common query pattern
            $table->index('recipe_id'); // Basic recipe lookup
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
