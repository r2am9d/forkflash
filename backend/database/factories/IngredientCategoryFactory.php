<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\IngredientCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IngredientCategory>
 */
final class IngredientCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            // Root categories
            'Proteins', 'Vegetables', 'Fruits', 'Grains & Starches', 'Dairy & Eggs',
            'Herbs & Spices', 'Oils & Fats', 'Condiments & Sauces', 'Baking Essentials', 'Beverages',
            
            // Sub-categories
            'Meat', 'Poultry', 'Seafood', 'Plant Proteins',
            'Leafy Greens', 'Root Vegetables', 'Cruciferous', 'Nightshades',
            'Citrus', 'Berries', 'Stone Fruits', 'Tropical Fruits',
            'Rice & Grains', 'Pasta', 'Bread', 'Potatoes',
            'Fresh Herbs', 'Dried Spices', 'Spice Blends',
            'Cooking Oils', 'Butter & Spreads',
            'Vinegars', 'Hot Sauces', 'Asian Sauces',
        ]);

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => fake()->optional(0.7)->sentence(),
            'parent_id' => null, // Will be set by specific factory states
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active' => fake()->boolean(95), // 95% active
        ];
    }

    /**
     * Create a root category (no parent).
     */
    public function root(): static
    {
        return $this->state(fn (array $attributes): array => [
            'parent_id' => null,
        ]);
    }

    /**
     * Create a subcategory with a parent.
     */
    public function withParent(IngredientCategory $parent): static
    {
        return $this->state(fn (array $attributes): array => [
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Create an inactive category.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
