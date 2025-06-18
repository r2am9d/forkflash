<?php

namespace Database\Factories;

use App\Models\RecipeReaction;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeReaction>
 */
class RecipeReactionFactory extends Factory
{
    protected $model = RecipeReaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reactionType = fake()->randomElement([
            'like', 'love', 'wow', 'helpful', 'tried_it', 
            'want_to_try', 'bookmarked', 'shared'
        ]);

        return [
            'recipe_id' => Recipe::factory(),
            'user_id' => User::factory(),
            'reaction_type' => $reactionType,
            'comment' => $this->getCommentForReaction($reactionType),
            'rating' => $this->getRatingForReaction($reactionType),
            'metadata' => $this->getMetadataForReaction($reactionType),
            'is_public' => fake()->boolean(85), // 85% public reactions
            'reacted_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Generate appropriate comment based on reaction type
     */
    private function getCommentForReaction(string $reactionType): ?string
    {
        // 70% chance of having a comment
        if (fake()->boolean(30)) {
            return null;
        }

        return match($reactionType) {
            'like' => fake()->randomElement([
                'Looks delicious!',
                'Great recipe!',
                'Love the ingredients.',
                'Can\'t wait to try this.',
                'Simple and tasty!',
                null
            ]),
            
            'love' => fake()->randomElement([
                'This is my new favorite recipe!',
                'Absolutely amazing! Made it twice already.',
                'Perfect for family dinners!',
                'Outstanding flavors!',
                'Best recipe I\'ve found for this dish.',
                'Love everything about this recipe!'
            ]),
            
            'wow' => fake()->randomElement([
                'Mind blown! Never thought to combine these ingredients.',
                'This is genius!',
                'What an amazing technique!',
                'Revolutionary approach to this dish!',
                'Incredible presentation ideas!',
                'This completely changed how I cook this dish.'
            ]),
            
            'helpful' => fake()->randomElement([
                'The tips section was super helpful!',
                'Great substitution suggestions.',
                'Perfect timing guidance.',
                'Love the troubleshooting tips.',
                'The technique explanations are clear.',
                'Helpful notes about equipment alternatives.'
            ]),
            
            'tried_it' => fake()->randomElement([
                'Made this last night - turned out perfectly!',
                'Followed the recipe exactly and it was delicious.',
                'Great results! Family loved it.',
                'Easy to follow and tasty results.',
                'Made a few tweaks but overall excellent.',
                'Tried this for a dinner party - huge hit!'
            ]),
            
            'want_to_try' => fake()->randomElement([
                'Adding this to my weekend cooking list!',
                'Looks perfect for our next family gathering.',
                'Can\'t wait to try this technique.',
                'Saving for when I have more time to cook.',
                'This would be perfect for meal prep.',
                null
            ]),
            
            'bookmarked' => fake()->randomElement([
                'Saved for later!',
                'Perfect for meal planning.',
                'Going in my favorites collection.',
                null,
                null,
                null
            ]),
            
            'shared' => fake()->randomElement([
                'Had to share this with my cooking group!',
                'My friends need to see this recipe.',
                'Sharing with family - they\'ll love this.',
                null,
                null,
                null
            ]),
            
            default => fake()->randomElement([
                'Great recipe!',
                'Thanks for sharing!',
                'Looks amazing!',
                null
            ])
        };
    }

    /**
     * Generate appropriate rating based on reaction type
     */
    private function getRatingForReaction(string $reactionType): ?int
    {
        return match($reactionType) {
            'love' => fake()->randomElement([5, 5, 5, 4]), // Mostly 5 stars
            'like' => fake()->randomElement([4, 4, 5, 3]), // Mostly 4-5 stars
            'helpful' => fake()->randomElement([4, 5, 4, 3]), // Good ratings
            'tried_it' => fake()->randomElement([3, 4, 5, 4, 5]), // Varied based on experience
            'wow' => fake()->randomElement([5, 5, 4]), // High ratings
            default => fake()->boolean(30) ? fake()->numberBetween(3, 5) : null // 30% chance of rating
        };
    }

    /**
     * Generate metadata based on reaction type
     */
    private function getMetadataForReaction(string $reactionType): ?array
    {
        if (fake()->boolean(40)) { // 40% chance of metadata
            return null;
        }

        return match($reactionType) {
            'tried_it' => [
                'cooking_notes' => fake()->randomElement([
                    'Used olive oil instead of butter',
                    'Cooked for 5 minutes longer',
                    'Added extra garlic',
                    'Served with rice instead of pasta',
                    'Doubled the recipe for leftovers'
                ]),
                'difficulty_experienced' => fake()->randomElement(['easy', 'moderate', 'challenging']),
                'time_taken' => fake()->numberBetween(15, 120) . ' minutes'
            ],
            
            'shared' => [
                'shared_to' => fake()->randomElement(['Facebook', 'Twitter', 'Instagram', 'Email', 'WhatsApp']),
                'shared_at' => fake()->dateTimeBetween('-1 week', 'now')->format('Y-m-d H:i:s')
            ],
            
            'bookmarked' => [
                'collection' => fake()->randomElement(['Dinner Ideas', 'Quick Meals', 'Favorites', 'To Try', 'Weekend Projects']),
                'notes' => fake()->randomElement(['For special occasions', 'Easy weeknight meal', 'Guest dinner', null])
            ],
            
            'want_to_try' => [
                'planned_date' => fake()->boolean(50) ? fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d') : null,
                'occasion' => fake()->randomElement(['dinner party', 'family meal', 'weekend cooking', 'meal prep'])
            ],
            
            default => null
        };
    }

    /**
     * Create a positive reaction (like, love, helpful, tried_it)
     */
    public function positive(): static
    {
        return $this->state(fn (array $attributes) => [
            'reaction_type' => fake()->randomElement(['like', 'love', 'helpful', 'tried_it']),
            'is_public' => true,
            'rating' => fake()->numberBetween(4, 5),
        ]);
    }

    /**
     * Create an engagement reaction (want_to_try, bookmarked, shared)
     */
    public function engagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'reaction_type' => fake()->randomElement(['want_to_try', 'bookmarked', 'shared']),
            'is_public' => fake()->boolean(70),
        ]);
    }

    /**
     * Create a reaction with a detailed comment
     */
    public function withComment(): static
    {
        return $this->state(fn (array $attributes) => [
            'comment' => fake()->paragraph(2),
            'is_public' => true,
        ]);
    }

    /**
     * Create a reaction with a rating
     */
    public function withRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(3, 5),
            'reaction_type' => fake()->randomElement(['like', 'love', 'tried_it', 'helpful']),
        ]);
    }

    /**
     * Create a recent reaction (within last week)
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'reacted_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Create a private reaction
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
