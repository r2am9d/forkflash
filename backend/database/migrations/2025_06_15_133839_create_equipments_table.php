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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('slug', 275)->unique();
            $table->string('category', 100)->nullable();
            $table->boolean('is_essential')->default(false);
            $table->json('alternatives')->nullable();
            $table->text('description')->nullable();
            $table->decimal('average_price', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['category', 'is_essential']);
            $table->index('is_essential');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
