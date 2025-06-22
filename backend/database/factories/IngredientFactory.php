<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ingredient>
 */
final class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ingredients = [
            // Proteins
            'chicken breast', 'ground beef', 'salmon fillet', 'tofu', 'eggs', 'black beans',
            'pork tenderloin', 'shrimp', 'greek yogurt', 'cottage cheese', 'turkey breast',

            // Vegetables
            'onion', 'garlic', 'carrot', 'celery', 'bell pepper', 'tomato', 'spinach',
            'broccoli', 'mushrooms', 'zucchini', 'potato', 'sweet potato', 'corn',

            // Fruits
            'lemon', 'lime', 'apple', 'banana', 'strawberries', 'blueberries', 'avocado',

            // Grains & Starches
            'rice', 'pasta', 'quinoa', 'oats', 'bread', 'flour', 'couscous',

            // Dairy
            'milk', 'butter', 'cheese', 'cream cheese', 'sour cream', 'heavy cream',

            // Herbs & Spices
            'basil', 'oregano', 'thyme', 'rosemary', 'parsley', 'cilantro', 'salt',
            'black pepper', 'garlic powder', 'paprika', 'cumin', 'chili powder',

            // Pantry
            'olive oil', 'vegetable oil', 'soy sauce', 'vinegar', 'honey', 'sugar',
            'baking powder', 'vanilla extract', 'chicken broth', 'tomato sauce',
        ];

        $name = fake()->randomElement($ingredients);

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'category_id' => IngredientCategory::factory(),
            'alternatives' => fake()->optional(0.6)->randomElements([
                'chicken thighs', 'turkey breast', 'ground turkey', 'tempeh', 'seitan',
                'mushrooms', 'cauliflower', 'broccoli', 'zucchini noodles', 'sweet potato',
            ], fake()->numberBetween(1, 3)),
        ];
    }

    /**
     * Create an ingredient with a specific category.
     */
    public function inCategory(IngredientCategory $category): static
    {
        return $this->state(fn (array $attributes): array => [
            'category_id' => $category->id,
        ]);
    }
}
