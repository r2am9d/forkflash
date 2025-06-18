<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeNote>
 */
class RecipeNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = RecipeNote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $notesByType = [
            'general' => [
                'This recipe serves 4-6 people',
                'Can be made ahead and refrigerated',
                'Freezes well for up to 3 months',
                'Great for meal prep',
                'Kid-friendly recipe',
                'Perfect for entertaining',
                'Easily doubles for larger crowds',
                'Best served immediately',
                'Comfort food at its finest',
                'Family recipe passed down for generations',
                'Quick and easy weeknight dinner',
                'Restaurant-quality results at home',
            ],
            'dietary' => [
                'Vegetarian-friendly',
                'Can be made vegan by substituting dairy',
                'Gluten-free if using certified ingredients',
                'Low in sodium',
                'High in protein',
                'Dairy-free option available',
                'Keto-friendly with modifications',
                'Contains nuts - check for allergies',
                'Naturally gluten-free',
                'High in fiber',
                'Low carb alternative available',
                'Heart-healthy ingredients',
            ],
            'storage' => [
                'Store leftovers in refrigerator for up to 3 days',
                'Can be frozen for up to 6 months',
                'Best consumed within 24 hours',
                'Reheat gently to maintain texture',
                'Store in airtight container',
                'Do not freeze - texture will change',
                'Can be stored at room temperature for 2 hours',
                'Refrigerate immediately after cooking',
                'Wrap tightly before refrigerating',
                'Use glass containers for best results',
                'Label with date before freezing',
                'Thaw overnight in refrigerator',
            ],
            'serving' => [
                'Serve with crusty bread',
                'Pairs well with a light salad',
                'Great with rice or pasta',
                'Serve over mashed potatoes',
                'Garnish with fresh herbs',
                'Drizzle with olive oil before serving',
                'Serve at room temperature',
                'Best served hot',
                'Accompany with roasted vegetables',
                'Perfect with a glass of wine',
                'Serve with lemon wedges',
                'Top with grated cheese',
            ],
        ];

        $noteType = $this->faker->randomElement(array_keys(RecipeNote::NOTE_TYPES));
        $noteText = $this->faker->randomElement($notesByType[$noteType]);

        return [
            'recipe_id' => Recipe::factory(),
            'note_text' => $noteText,
            'note_type' => $noteType,
            'display_order' => $this->faker->numberBetween(0, 10),
            'is_public' => $this->faker->boolean(90), // 90% public
            'created_by_user_id' => $this->faker->boolean(30) ? User::factory() : null, // 30% have creator
        ];
    }

    /**
     * Create a general note.
     */
    public function general(): static
    {
        return $this->state(fn(array $attributes) => [
            'note_type' => 'general',
        ]);
    }

    /**
     * Create a dietary note.
     */
    public function dietary(): static
    {
        return $this->state(fn(array $attributes) => [
            'note_type' => 'dietary',
        ]);
    }

    /**
     * Create a storage note.
     */
    public function storage(): static
    {
        return $this->state(fn(array $attributes) => [
            'note_type' => 'storage',
        ]);
    }

    /**
     * Create a serving note.
     */
    public function serving(): static
    {
        return $this->state(fn(array $attributes) => [
            'note_type' => 'serving',
        ]);
    }

    /**
     * Create a private note.
     */
    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Create a note with specific display order.
     */
    public function ordered(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'display_order' => $order,
        ]);
    }
}
