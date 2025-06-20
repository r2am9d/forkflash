<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Recipe;
use App\Models\RecipeEquipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeEquipment>
 */
class RecipeEquipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = RecipeEquipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usageNotes = [
            'Use large size for best results',
            'Medium heat setting recommended',
            'Non-stick preferred',
            'Heavy-bottomed works best',
            'Can substitute with similar sized alternative',
            'Preheat before use',
            'Season well before cooking',
            'Use wooden utensils to avoid scratching',
            'Digital thermometer preferred for accuracy',
            'Food processor can substitute if needed',
            'Sharp knife essential for proper cuts',
            'Fine mesh strainer works best',
            'Room temperature ingredients mix better',
            'Chill bowl beforehand for best whipping',
            'Use parchment paper for easy cleanup',
            'Stir frequently to prevent sticking',
            'Let rest before serving',
            'Clean immediately after use',
            null, null, // Some equipment doesn't need notes
        ];

        return [
            'recipe_id' => Recipe::factory(),
            'equipment_id' => Equipment::factory(),
            'is_required' => $this->faker->boolean(80), // 80% chance of being required
            'notes' => $this->faker->randomElement($usageNotes),
            'sort' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Create a required equipment.
     */
    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_required' => true,
        ]);
    }

    /**
     * Create an optional equipment.
     */
    public function optional(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_required' => false,
        ]);
    }

    /**
     * Create equipment with specific notes.
     */
    public function withNotes(string $notes): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $notes,
        ]);
    }

    /**
     * Create equipment for a specific recipe.
     */
    public function forRecipe(Recipe $recipe): static
    {
        return $this->state(fn (array $attributes) => [
            'recipe_id' => $recipe->id,
        ]);
    }

    /**
     * Create equipment using specific equipment.
     */
    public function usingEquipment(Equipment $equipment): static
    {
        return $this->state(fn (array $attributes) => [
            'equipment_id' => $equipment->id,
        ]);
    }

    /**
     * Create equipment with specific display order.
     */
    public function withOrder(int $order): static
    {
        return $this->state(fn (array $attributes) => [
            'sort' => $order,
        ]);
    }

    /**
     * Create essential cooking equipment (always required).
     */
    public function essential(): static
    {
        $essentialNotes = [
            'Essential for this recipe',
            'Cannot be substituted',
            'Critical for proper cooking',
            'Required for safety',
            'Must have for best results',
        ];

        return $this->state(fn (array $attributes) => [
            'is_required' => true,
            'notes' => $this->faker->randomElement($essentialNotes),
        ]);
    }

    /**
     * Create baking-specific equipment.
     */
    public function baking(): static
    {
        $bakingNotes = [
            'Preheat oven to specified temperature',
            'Line with parchment paper for easy removal',
            'Grease pan thoroughly',
            'Use room temperature ingredients',
            'Don\'t overmix batter',
            'Cool completely before frosting',
            'Sift dry ingredients for best texture',
        ];

        return $this->state(fn (array $attributes) => [
            'notes' => $this->faker->randomElement($bakingNotes),
        ]);
    }

    /**
     * Create stovetop cooking equipment.
     */
    public function stovetop(): static
    {
        $stovetopNotes = [
            'Heat over medium heat',
            'Don\'t overcrowd the pan',
            'Stir frequently to prevent sticking',
            'Season the pan first',
            'Let it get hot before adding food',
            'Use wooden utensils to avoid scratching',
            'Keep heat consistent throughout',
        ];

        return $this->state(fn (array $attributes) => [
            'notes' => $this->faker->randomElement($stovetopNotes),
        ]);
    }

    /**
     * Create specialized equipment that's usually optional.
     */
    public function specialized(): static
    {
        $specializedNotes = [
            'Nice to have but not essential',
            'Makes the process easier',
            'Can be substituted with basic tools',
            'Recommended for best results',
            'Professional grade preferred',
            'Alternative methods available',
        ];

        return $this->state(fn (array $attributes) => [
            'is_required' => false,
            'notes' => $this->faker->randomElement($specializedNotes),
        ]);
    }
}
