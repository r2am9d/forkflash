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
            $table->json('common_substitutes')->nullable(); // ["chicken thighs", "turkey breast"]
            $table->json('storage_info')->nullable(); // {"refrigerator": "3-5 days", "freezer": "6 months"}
            $table->json('dietary_flags')->nullable(); // {"vegetarian": true, "vegan": false, "gluten_free": true}
            $table->decimal('average_price', 8, 2)->nullable(); // Price per unit
            $table->string('price_unit', 50)->nullable(); // "per lb", "per item", "per kg"
            $table->json('seasonality')->nullable(); // [3, 4, 5, 6] for spring/summer months
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('name');
            $table->index('slug'); 
            $table->index('category_id');
            $table->index(['category_id', 'name']); // Category filtering with name search
            $table->index('average_price'); // Price filtering
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
