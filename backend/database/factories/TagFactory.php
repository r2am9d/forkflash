<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Tag::class;

    /**
     * Predefined realistic recipe tags - simplified for mobile performance.
     */
    private static $tags = [
        // Quick popular tags users would commonly use
        ['name' => 'Vegetarian', 'color' => '#10B981'],
        ['name' => 'Vegan', 'color' => '#059669'],
        ['name' => 'Gluten-Free', 'color' => '#F59E0B'],
        ['name' => 'Keto', 'color' => '#8B5CF6'],
        ['name' => 'Quick', 'color' => '#10B981'],
        ['name' => 'Easy', 'color' => '#3B82F6'],
        ['name' => 'Healthy', 'color' => '#10B981'],
        ['name' => 'Spicy', 'color' => '#DC2626'],
        ['name' => 'Sweet', 'color' => '#EC4899'],
        ['name' => 'Comfort Food', 'color' => '#92400E'],
        ['name' => 'Italian', 'color' => '#DC2626'],
        ['name' => 'Mexican', 'color' => '#059669'],
        ['name' => 'Asian', 'color' => '#F59E0B'],
        ['name' => 'Dessert', 'color' => '#EC4899'],
        ['name' => 'Breakfast', 'color' => '#FBBF24'],
        ['name' => 'Dinner', 'color' => '#DC2626'],
        ['name' => 'Baked', 'color' => '#A78BFA'],
        ['name' => 'Grilled', 'color' => '#F97316'],
        ['name' => 'One-Pot', 'color' => '#F97316'],
        ['name' => 'No-Cook', 'color' => '#10B981'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $tagIndex = 0;
        
        // Use predefined tags if available, otherwise generate random
        if ($tagIndex < count(self::$tags)) {
            $tag = self::$tags[$tagIndex];
            $tagIndex++;
            
            return [
                'name' => $tag['name'],
                'slug' => Str::slug($tag['name']),
                'color' => $tag['color'],
                'usage_count' => $this->faker->numberBetween(0, 100),
            ];
        }

        // Fallback to random generation
        $colors = ['#DC2626', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#F97316', '#059669'];
        $name = $this->faker->words(2, true);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'color' => $this->faker->randomElement($colors),
            'usage_count' => $this->faker->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the tag is popular (high usage count).
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_count' => $this->faker->numberBetween(100, 500),
        ]);
    }
}
