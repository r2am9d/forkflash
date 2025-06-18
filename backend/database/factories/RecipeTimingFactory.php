<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeTiming;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeTiming>
 */
class RecipeTimingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = RecipeTiming::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prepTime = $this->faker->numberBetween(5, 45); // 5-45 minutes prep
        $cookTime = $this->faker->numberBetween(10, 120); // 10-120 minutes cook
        $totalTime = $prepTime + $cookTime + $this->faker->numberBetween(0, 15); // Add some buffer time

        $handsOnTime = $this->faker->numberBetween($prepTime, $prepTime + ($cookTime * 0.3));
        $passiveTime = $totalTime - $handsOnTime;

        return [
            'recipe_id' => Recipe::factory(),
            'prep_minutes' => $prepTime,
            'cook_minutes' => $cookTime,
            'total_minutes' => $totalTime,
            'hands_on_time' => $handsOnTime,
            'passive_time' => max(0, $passiveTime), // Ensure passive time is not negative
            'difficulty_level' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'servings_time_multiplier' => $this->faker->randomFloat(2, 0.8, 1.2), // Time scales 80%-120% with servings
            'timing_notes' => $this->faker->optional(0.3)->randomElement([
                'Allow extra time for first attempt',
                'Can be prepared in advance',
                'Most time is hands-off',
                'Prep work can be done ahead',
                'Requires attention during cooking',
                'Perfect for meal prep',
                'Great for busy weeknight',
                'Weekend project recipe',
                'Some steps can be done in parallel',
                'Marinating time not included',
            ]),
        ];
    }

    /**
     * Create a quick recipe (30 minutes or less).
     */
    public function quick(): static
    {
        return $this->state(function (array $attributes) {
            $prepTime = $this->faker->numberBetween(5, 15);
            $cookTime = $this->faker->numberBetween(5, 20);
            $totalTime = min($prepTime + $cookTime, 30);

            return [
                'prep_minutes' => $prepTime,
                'cook_minutes' => $cookTime,
                'total_minutes' => $totalTime,
                'hands_on_time' => $this->faker->numberBetween($prepTime, $totalTime),
                'passive_time' => $this->faker->numberBetween(0, 5),
                'difficulty_level' => $this->faker->randomElement(['easy', 'medium']),
                'timing_notes' => $this->faker->randomElement([
                    'Perfect for busy weeknight',
                    'Ready in under 30 minutes',
                    'Quick and easy',
                    'Great for last-minute meals',
                ]),
            ];
        });
    }

    /**
     * Create an easy recipe.
     */
    public function easy(): static
    {
        return $this->state(function (array $attributes) {
            $prepTime = $this->faker->numberBetween(5, 20);
            $cookTime = $this->faker->numberBetween(10, 40);

            return [
                'prep_minutes' => $prepTime,
                'cook_minutes' => $cookTime,
                'total_minutes' => $prepTime + $cookTime + $this->faker->numberBetween(0, 10),
                'difficulty_level' => 'easy',
                'servings_time_multiplier' => $this->faker->randomFloat(2, 0.9, 1.1),
                'timing_notes' => $this->faker->randomElement([
                    'Perfect for beginners',
                    'Simple and straightforward',
                    'Minimal technique required',
                    'Hard to mess up',
                ]),
            ];
        });
    }

    /**
     * Create a medium difficulty recipe.
     */
    public function medium(): static
    {
        return $this->state(function (array $attributes) {
            $prepTime = $this->faker->numberBetween(15, 35);
            $cookTime = $this->faker->numberBetween(20, 60);

            return [
                'prep_minutes' => $prepTime,
                'cook_minutes' => $cookTime,
                'total_minutes' => $prepTime + $cookTime + $this->faker->numberBetween(5, 15),
                'difficulty_level' => 'medium',
                'servings_time_multiplier' => $this->faker->randomFloat(2, 0.85, 1.15),
                'timing_notes' => $this->faker->randomElement([
                    'Requires some cooking experience',
                    'Multiple steps involved',
                    'Timing is important',
                    'Some technique required',
                ]),
            ];
        });
    }

    /**
     * Create a hard difficulty recipe.
     */
    public function hard(): static
    {
        return $this->state(function (array $attributes) {
            $prepTime = $this->faker->numberBetween(20, 60);
            $cookTime = $this->faker->numberBetween(30, 180);

            return [
                'prep_minutes' => $prepTime,
                'cook_minutes' => $cookTime,
                'total_minutes' => $prepTime + $cookTime + $this->faker->numberBetween(10, 30),
                'difficulty_level' => 'hard',
                'servings_time_multiplier' => $this->faker->randomFloat(2, 0.8, 1.2),
                'timing_notes' => $this->faker->randomElement([
                    'Advanced technique required',
                    'Multiple complex steps',
                    'Precise timing critical',
                    'Professional-level recipe',
                    'Allow extra time for first attempt',
                ]),
            ];
        });
    }

    /**
     * Create a slow cooking recipe (over 2 hours).
     */
    public function slow(): static
    {
        return $this->state(function (array $attributes) {
            $prepTime = $this->faker->numberBetween(10, 30);
            $cookTime = $this->faker->numberBetween(120, 480); // 2-8 hours
            $totalTime = $prepTime + $cookTime;
            $handsOnTime = $prepTime + $this->faker->numberBetween(10, 30);

            return [
                'prep_minutes' => $prepTime,
                'cook_minutes' => $cookTime,
                'total_minutes' => $totalTime,
                'hands_on_time' => $handsOnTime,
                'passive_time' => $totalTime - $handsOnTime,
                'difficulty_level' => $this->faker->randomElement(['easy', 'medium']),
                'timing_notes' => $this->faker->randomElement([
                    'Most time is hands-off',
                    'Perfect for slow cooker',
                    'Great for weekend cooking',
                    'Requires patience but worth it',
                    'Set it and forget it',
                ]),
            ];
        });
    }

    /**
     * Create a baking recipe with typical baking times.
     */
    public function baking(): static
    {
        return $this->state(function (array $attributes) {
            $prepTime = $this->faker->numberBetween(15, 45);
            $bakeTime = $this->faker->numberBetween(20, 90);
            $totalTime = $prepTime + $bakeTime + $this->faker->numberBetween(10, 20); // Include cooling time

            return [
                'prep_minutes' => $prepTime,
                'cook_minutes' => $bakeTime,
                'total_minutes' => $totalTime,
                'hands_on_time' => $prepTime + $this->faker->numberBetween(5, 10),
                'passive_time' => $bakeTime + $this->faker->numberBetween(10, 20),
                'difficulty_level' => $this->faker->randomElement(['medium', 'hard']),
                'timing_notes' => $this->faker->randomElement([
                    'Includes cooling time',
                    'Don\'t open oven door while baking',
                    'Test for doneness before removing',
                    'Allow to cool completely',
                ]),
            ];
        });
    }
}
