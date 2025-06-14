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
        Schema::create('grocery_lists', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique(); // ULID for public identification
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_template')->default(false); // For reusable list templates
            $table->boolean('is_shared')->default(false); // For shared lists
            $table->json('shared_with')->nullable(); // Array of user IDs who can access this list
            $table->json('tags')->nullable(); // Array of tags for categorization
            $table->timestamp('completed_at')->nullable(); // When the shopping was completed
            $table->json('metadata')->nullable(); // Flexible field for future features (store preferences, etc.)
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('ulid'); // Primary index for public queries
            $table->index('user_id'); // Index for user's lists queries
            $table->index('name');
            $table->index('is_template');
            $table->index('is_shared');
            $table->index('completed_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grocery_lists');
    }
};
