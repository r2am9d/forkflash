<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeIngredient>
 */
class RecipeIngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = RecipeIngredient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common cooking preparation methods
        $preparations = [
            'diced', 'chopped', 'minced', 'sliced', 'julienned', 'grated',
            'crushed', 'peeled', 'trimmed', 'cubed', 'shredded', 'finely chopped',
            'roughly chopped', 'thinly sliced', 'halved', 'quartered',
            'separated', 'beaten', 'melted', 'softened', 'room temperature',
            null, null, null // Some ingredients don't need preparation notes
        ];

        return [
            'recipe_id' => Recipe::factory(),
            'ingredient_id' => Ingredient::factory(),
            'quantity' => $this->faker->randomFloat(2, 0.25, 10),
            'unit_id' => Unit::factory(),
            'preparation_notes' => $this->faker->randomElement($preparations),
            'is_optional' => $this->faker->boolean(20), // 20% chance of being optional
            'display_order' => $this->faker->numberBetween(1, 15),
        ];
    }

    /**
     * Create a recipe ingredient with specific quantity.
     */
    public function withQuantity(float $quantity): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
        ]);
    }

    /**
     * Create a recipe ingredient without a unit (for items that are counted).
     */
    public function withoutUnit(): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_id' => null,
        ]);
    }

    /**
     * Create an optional ingredient.
     */
    public function optional(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_optional' => true,
        ]);
    }

    /**
     * Create a required ingredient.
     */
    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_optional' => false,
        ]);
    }

    /**
     * Create an ingredient with specific preparation notes.
     */
    public function withPreparation(string $preparation): static
    {
        return $this->state(fn (array $attributes) => [
            'preparation_notes' => $preparation,
        ]);
    }

    /**
     * Create an ingredient without preparation notes.
     */
    public function withoutPreparation(): static
    {
        return $this->state(fn (array $attributes) => [
            'preparation_notes' => null,
        ]);
    }

    /**
     * Create an ingredient with specific display order.
     */
    public function withOrder(int $order): static
    {
        return $this->state(fn (array $attributes) => [
            'display_order' => $order,
        ]);
    }

    /**
     * Create ingredients for a specific recipe.
     */
    public function forRecipe(Recipe $recipe): static
    {
        return $this->state(fn (array $attributes) => [
            'recipe_id' => $recipe->id,
        ]);
    }

    /**
     * Create ingredients using a specific ingredient.
     */
    public function usingIngredient(Ingredient $ingredient): static
    {
        return $this->state(fn (array $attributes) => [
            'ingredient_id' => $ingredient->id,
        ]);
    }

    /**
     * Create ingredients with a specific unit.
     */
    public function withUnit(Unit $unit): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    /**
     * Create typical baking ingredients (precise measurements).
     */
    public function baking(): static
    {
        $bakingPreparations = [
            'sifted', 'room temperature', 'softened', 'melted', 'beaten',
            'separated', 'at room temperature', null
        ];

        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->randomFloat(2, 0.5, 4),
            'preparation_notes' => $this->faker->randomElement($bakingPreparations),
            'is_optional' => false, // Baking ingredients are usually required
        ]);
    }

    /**
     * Create typical protein ingredients.
     */
    public function protein(): static
    {
        $proteinPreparations = [
            'boneless, skinless', 'cut into pieces', 'trimmed', 'cubed',
            'ground', 'sliced', 'filleted', 'deboned', null
        ];

        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->randomFloat(2, 1, 3),
            'preparation_notes' => $this->faker->randomElement($proteinPreparations),
            'is_optional' => false,
        ]);
    }

    /**
     * Create typical vegetable ingredients.
     */
    public function vegetable(): static
    {
        $vegetablePreparations = [
            'diced', 'chopped', 'sliced', 'julienned', 'minced',
            'roughly chopped', 'thinly sliced', 'peeled and diced',
            'peeled and chopped', null
        ];

        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->randomFloat(2, 0.5, 2),
            'preparation_notes' => $this->faker->randomElement($vegetablePreparations),
        ]);
    }

    /**
     * Create typical spice/seasoning ingredients (small amounts).
     */
    public function seasoning(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->randomFloat(2, 0.25, 2),
            'preparation_notes' => null,
            'is_optional' => $this->faker->boolean(30), // Seasonings more likely to be optional
        ]);
    }
}
