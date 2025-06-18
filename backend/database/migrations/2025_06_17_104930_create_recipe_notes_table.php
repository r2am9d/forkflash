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
        Schema::create('recipe_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->text('note_text'); // The actual note content
            $table->enum('note_type', ['general', 'dietary', 'storage', 'serving'])->default('general'); // Categorize notes
            $table->integer('display_order')->default(0); // Order to display notes
            $table->boolean('is_public')->default(true); // Allow private notes in the future
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Track who added the note
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipe_id', 'display_order']); // Common query pattern
            $table->index(['note_type']); // Filter by note type
            $table->index(['recipe_id', 'note_type']); // Filter notes by type for a recipe
            $table->index(['created_by_user_id']); // User's notes
            
            // Full-text search index for note content (for "find recipes with X notes")
            $table->index(['recipe_id']); // Basic recipe lookup
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_notes');
    }
};
