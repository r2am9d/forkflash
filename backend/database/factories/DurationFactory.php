<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\Duration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Duration>
 */
final class DurationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Duration::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prepMinutes = $this->faker->numberBetween(5, 45);
        $cookMinutes = $this->faker->numberBetween(10, 120);
        $totalMinutes = $prepMinutes + $cookMinutes;

        return [
            'recipe_id' => Recipe::factory(),
            'prep_minutes' => $prepMinutes,
            'cook_minutes' => $cookMinutes,
            'total_minutes' => $totalMinutes,
        ];
    }

    /**
     * Generate quick recipes (under 30 minutes total).
     */
    public function quick(): static
    {
        return $this->state(function (array $attributes) {
            $prepMinutes = $this->faker->numberBetween(5, 15);
            $cookMinutes = $this->faker->numberBetween(5, 15);
            $totalMinutes = $prepMinutes + $cookMinutes;

            return [
                'prep_minutes' => $prepMinutes,
                'cook_minutes' => $cookMinutes,
                'total_minutes' => $totalMinutes,
            ];
        });
    }

    /**
     * Generate slow cooking recipes (over 2 hours).
     */
    public function slowCook(): static
    {
        return $this->state(function (array $attributes) {
            $prepMinutes = $this->faker->numberBetween(15, 30);
            $cookMinutes = $this->faker->numberBetween(120, 480); // 2-8 hours
            $totalMinutes = $prepMinutes + $cookMinutes;

            return [
                'prep_minutes' => $prepMinutes,
                'cook_minutes' => $cookMinutes,
                'total_minutes' => $totalMinutes,
            ];
        });
    }
}
