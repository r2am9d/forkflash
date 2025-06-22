<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\Trick;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trick>
 */
final class TrickFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Trick::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tricks = [
            'Use room temperature eggs for better mixing',
            'Toast spices before adding to enhance flavor',
            'Let meat rest after cooking for juicier results',
            'Add salt to pasta water - it should taste like seawater',
            'Preheat your pan before adding oil',
            'Use a kitchen scale for accurate measurements',
            'Taste as you go and adjust seasonings',
            'Keep knives sharp for safer, easier cutting',
            'Read the entire recipe before starting',
            'Prep all ingredients before you start cooking',
        ];

        return [
            'recipe_id' => Recipe::factory(),
            'content' => $this->faker->randomElement($tricks),
            'type' => $this->faker->randomElement(['trick', 'tip', 'note']),
            'sort' => $this->faker->numberBetween(0, 10),
        ];
    }

    /**
     * Generate cooking tricks specifically.
     */
    public function trick(): static
    {
        return $this->state([
            'type' => 'trick',
            'content' => $this->faker->randomElement([
                'Use room temperature ingredients for better mixing',
                'Toast nuts and spices to enhance their flavor',
                'Let meat rest 5-10 minutes after cooking',
                'Add salt to pasta water until it tastes like seawater',
                'Preheat your pan before adding oil to prevent sticking',
                'Use a kitchen scale for more accurate baking',
                'Taste and adjust seasonings throughout cooking',
            ]),
        ]);
    }

    /**
     * Generate cooking tips specifically.
     */
    public function tip(): static
    {
        return $this->state([
            'type' => 'tip',
            'content' => $this->faker->randomElement([
                'This recipe can be made ahead and reheated',
                'Substitute ingredients based on what you have available',
                'Double the recipe for meal prep',
                'Leftovers keep well in the refrigerator for 3 days',
                'Can be frozen for up to 3 months',
            ]),
        ]);
    }

    /**
     * Generate general notes.
     */
    public function note(): static
    {
        return $this->state([
            'type' => 'note',
            'content' => $this->faker->randomElement([
                'Recipe serves 4-6 people generously',
                'Cooking time may vary based on your equipment',
                'Adjust spice levels to your preference',
                'Best served immediately while hot',
                'Pairs well with a simple green salad',
            ]),
        ]);
    }
}
