<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
final class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dishTypes = ['pasta', 'chicken', 'beef', 'seafood', 'vegetarian', 'dessert', 'soup', 'salad'];
        $cookingMethods = ['grilled', 'baked', 'fried', 'steamed', 'roasted', 'braised'];

        $dishType = fake()->randomElement($dishTypes);
        $cookingMethod = fake()->randomElement($cookingMethods);

        return [
            'user_id' => User::factory(),
            'name' => ucwords(sprintf('%s %s ', $cookingMethod, $dishType).fake()->word()),
            'url' => fake()->url(),
            'image' => fake()->imageUrl(640, 480, 'food'),
            'summary' => fake()->optional(0.7)->paragraph(),
            'servings' => fake()->numberBetween(1, 8),
            'info' => [
                'Prep: '.fake()->numberBetween(5, 30).' minutes',
                'Cook: '.fake()->numberBetween(10, 120).' minutes',
                'Total: '.fake()->numberBetween(15, 150).' minutes',
            ],
            'ingredients' => $this->generateIngredients(),
            'equipments' => $this->generateEquipments(),
            'notes' => fake()->optional(0.3)->sentences(fake()->numberBetween(1, 3)),
            'nutrition' => [
                'Calories: '.fake()->numberBetween(200, 800).' kcal',
                'Protein: '.fake()->numberBetween(10, 50).'g',
                'Carbs: '.fake()->numberBetween(20, 100).'g',
                'Fat: '.fake()->numberBetween(5, 30).'g',
            ],
            'tips' => fake()->optional(0.4)->sentences(fake()->numberBetween(1, 2)),
            'video' => fake()->optional(0.2)->url(),
        ];
    }

    /**
     * Create a recipe with a specific cuisine type.
     */
    public function cuisine(string $cuisine): static
    {
        return $this->state(fn (array $attributes): array => [
            'name' => ucwords($cuisine.' '.fake()->word().' '.fake()->word()),
        ]);
    }

    /**
     * Create a quick recipe (under 30 minutes).
     */
    public function quick(): static
    {
        return $this->state(fn (array $attributes): array => [
            'info' => [
                'Prep: '.fake()->numberBetween(5, 10).' minutes',
                'Cook: '.fake()->numberBetween(10, 20).' minutes',
                'Total: '.fake()->numberBetween(15, 30).' minutes',
            ],
        ]);
    }

    /**
     * Generate realistic ingredients list.
     *
     * @return array<int, string>
     */
    private function generateIngredients(): array
    {
        $ingredients = [
            '2 cups all-purpose flour',
            '1 lb chicken breast',
            '2 tablespoons olive oil',
            '1 medium onion, diced',
            '3 cloves garlic, minced',
            '1 cup chicken broth',
            '1/2 cup heavy cream',
            '1 teaspoon salt',
            '1/2 teaspoon black pepper',
            '1 tablespoon fresh herbs',
            '2 large eggs',
            '1 cup shredded cheese',
            '1/4 cup butter',
            '2 tablespoons tomato paste',
            '1 bell pepper, sliced',
        ];

        return fake()->randomElements($ingredients, fake()->numberBetween(5, 10));
    }

    /**
     * Generate realistic equipment list.
     *
     * @return array<int, string>
     */
    private function generateEquipments(): array
    {
        $equipments = [
            'Large skillet',
            'Medium saucepan',
            'Cutting board',
            'Sharp knife',
            'Wooden spoon',
            'Measuring cups',
            'Measuring spoons',
            'Mixing bowl',
            'Baking dish',
            'Oven',
        ];

        return fake()->randomElements($equipments, fake()->numberBetween(3, 6));
    }
}
