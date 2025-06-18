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
            'common_substitutes' => fake()->optional(0.6)->randomElements([
                'chicken thighs', 'turkey breast', 'ground turkey', 'tempeh', 'seitan',
                'mushrooms', 'cauliflower', 'broccoli', 'zucchini noodles', 'sweet potato',
            ], fake()->numberBetween(1, 3)),
            'storage_info' => fake()->optional(0.7)->randomElement([
                ['refrigerator' => '3-5 days', 'freezer' => '6 months'],
                ['pantry' => '1 year', 'refrigerator' => '6 months'],
                ['refrigerator' => '1 week', 'room_temperature' => '2 days'],
                ['freezer' => '12 months', 'refrigerator' => '2 weeks'],
            ]),
            'dietary_flags' => $this->generateDietaryFlags(),
            'average_price' => fake()->optional(0.8)->randomFloat(2, 0.50, 25.00),
            'price_unit' => fake()->optional(0.8)->randomElement([
                'per lb', 'per kg', 'per item', 'per bunch', 'per package', 'per dozen'
            ]),
            'seasonality' => fake()->optional(0.4)->randomElements([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], fake()->numberBetween(2, 6)),
        ];
    }

    /**
     * Generate realistic dietary flags.
     *
     * @return array<string, bool>
     */
    private function generateDietaryFlags(): array
    {
        // Base probabilities for different dietary flags
        return [
            'vegetarian' => fake()->boolean(70), // 70% vegetarian
            'vegan' => fake()->boolean(40), // 40% vegan (subset of vegetarian)
            'gluten_free' => fake()->boolean(80), // 80% gluten free
            'dairy_free' => fake()->boolean(60), // 60% dairy free
            'nut_free' => fake()->boolean(90), // 90% nut free
            'soy_free' => fake()->boolean(85), // 85% soy free
            'organic' => fake()->boolean(30), // 30% organic
        ];
    }

    /**
     * Create a vegetarian ingredient.
     */
    public function vegetarian(): static
    {
        return $this->state(fn (array $attributes): array => [
            'dietary_flags' => array_merge($attributes['dietary_flags'] ?? [], [
                'vegetarian' => true,
            ]),
        ]);
    }

    /**
     * Create a vegan ingredient.
     */
    public function vegan(): static
    {
        return $this->state(fn (array $attributes): array => [
            'dietary_flags' => array_merge($attributes['dietary_flags'] ?? [], [
                'vegetarian' => true,
                'vegan' => true,
                'dairy_free' => true,
            ]),
        ]);
    }

    /**
     * Create a gluten-free ingredient.
     */
    public function glutenFree(): static
    {
        return $this->state(fn (array $attributes): array => [
            'dietary_flags' => array_merge($attributes['dietary_flags'] ?? [], [
                'gluten_free' => true,
            ]),
        ]);
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
