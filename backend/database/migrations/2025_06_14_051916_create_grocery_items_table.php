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
        Schema::create('grocery_items', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique(); // ULID for public identification
            $table->foreignId('grocery_list_id')->constrained('grocery_lists', 'id')->onDelete('cascade');
            $table->string('name'); // Item name (e.g., "Tomatoes", "Milk")
            $table->string('category')->nullable(); // Category (e.g., "Produce", "Dairy", "Meat")
            $table->decimal('quantity', 8, 2)->nullable(); // Quantity needed
            $table->foreignId('unit_id')->nullable()->constrained('units', 'id')->onDelete('set null');
            $table->text('notes')->nullable(); // Additional notes (e.g., "Organic", "Brand preference")
            $table->boolean('is_checked')->default(false); // Whether item is checked off
            $table->timestamp('checked_at')->nullable(); // When item was checked
            $table->integer('sort_order')->default(0); // For custom sorting
            $table->decimal('estimated_price', 8, 2)->nullable(); // Estimated price for budgeting
            $table->foreignId('recipe_id')->nullable()->constrained('recipes', 'id')->onDelete('set null'); // Source recipe if generated from recipe
            $table->json('metadata')->nullable(); // Flexible field (store location, aisle, etc.)
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('ulid'); // Primary index for public queries
            $table->index(['grocery_list_id', 'sort_order']); // For ordered item lists
            $table->index(['grocery_list_id', 'is_checked']); // For checked/unchecked filtering
            $table->index(['grocery_list_id', 'category']); // For category grouping
            $table->index('recipe_id'); // For recipe-linked items
            $table->index('name'); // For searching items
            $table->index('unit_id'); // For unit-based queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grocery_items');
    }
};
