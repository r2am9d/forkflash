<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
final class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'url' => fake()->imageUrl(800, 600, 'food'),
            'is_primary' => false,
            'sort' => 0,
        ];
    }

    /**
     * Create a primary image.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_primary' => true,
        ]);
    }

    /**
     * Create image with specific sort order.
     */
    public function order(int $order): static
    {
        return $this->state(fn (array $attributes): array => [
            'sort' => $order,
        ]);
    }

    /**
     * Create image with specific dimensions.
     */
    public function dimensions(int $width, int $height): static
    {
        return $this->state(fn (array $attributes): array => [
            'url' => fake()->imageUrl($width, $height, 'food'),
        ]);
    }
}
