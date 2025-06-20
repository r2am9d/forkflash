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
            // Simple flat categories for mobile navigation
            'Proteins',
            'Vegetables', 
            'Fruits',
            'Grains & Starches',
            'Dairy & Eggs',
            'Herbs & Spices',
            'Oils & Fats',
            'Condiments & Sauces',
            'Baking Essentials',
            'Beverages',
            'Seafood',
            'Nuts & Seeds',
            'Legumes',
            'Sweeteners',
        ]);

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
        ];
    }
}
