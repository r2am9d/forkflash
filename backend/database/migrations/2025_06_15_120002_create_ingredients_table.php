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
        Schema::create('ingredients', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('slug', 275)->unique();
            $table->foreignId('category_id')->constrained('ingredient_categories', 'id')->onDelete('cascade');
            $table->json('alternatives')->nullable(); // ["chicken thighs", "turkey breast"]
            $table->timestamps();

            // Indexes for performance
            $table->index('name');
            $table->index('slug'); 
            $table->index('category_id');
            $table->index(['category_id', 'name']); // Category filtering with name search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
