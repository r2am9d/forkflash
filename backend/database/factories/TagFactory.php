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
     * Predefined realistic recipe tags with categories and colors.
     */
    private static $tags = [
        // Dietary Restrictions
        ['name' => 'Vegetarian', 'category' => 'dietary', 'color' => '#10B981', 'is_dietary_restriction' => true, 'description' => 'Contains no meat or fish'],
        ['name' => 'Vegan', 'category' => 'dietary', 'color' => '#059669', 'is_dietary_restriction' => true, 'description' => 'Contains no animal products'],
        ['name' => 'Gluten-Free', 'category' => 'dietary', 'color' => '#F59E0B', 'is_dietary_restriction' => true, 'description' => 'Contains no gluten'],
        ['name' => 'Dairy-Free', 'category' => 'dietary', 'color' => '#3B82F6', 'is_dietary_restriction' => true, 'description' => 'Contains no dairy products'],
        ['name' => 'Keto', 'category' => 'dietary', 'color' => '#8B5CF6', 'is_dietary_restriction' => true, 'description' => 'Low-carb, high-fat diet'],
        ['name' => 'Paleo', 'category' => 'dietary', 'color' => '#F97316', 'is_dietary_restriction' => true, 'description' => 'Based on paleolithic diet'],
        ['name' => 'Low-Carb', 'category' => 'dietary', 'color' => '#EF4444', 'is_dietary_restriction' => true, 'description' => 'Limited carbohydrate content'],

        // Cooking Methods
        ['name' => 'Baked', 'category' => 'cooking-method', 'color' => '#A78BFA', 'description' => 'Cooked in an oven'],
        ['name' => 'Grilled', 'category' => 'cooking-method', 'color' => '#F97316', 'description' => 'Cooked on a grill'],
        ['name' => 'Pan-Fried', 'category' => 'cooking-method', 'color' => '#FBBF24', 'description' => 'Fried in a pan with oil'],
        ['name' => 'Slow-Cooked', 'category' => 'cooking-method', 'color' => '#92400E', 'description' => 'Cooked slowly over low heat'],
        ['name' => 'Roasted', 'category' => 'cooking-method', 'color' => '#DC2626', 'description' => 'Cooked in an oven at high temperature'],
        ['name' => 'Steamed', 'category' => 'cooking-method', 'color' => '#06B6D4', 'description' => 'Cooked with steam'],
        ['name' => 'No-Cook', 'category' => 'cooking-method', 'color' => '#10B981', 'description' => 'Requires no cooking'],

        // Flavor Profiles
        ['name' => 'Spicy', 'category' => 'flavor', 'color' => '#DC2626', 'description' => 'Hot and spicy flavors'],
        ['name' => 'Sweet', 'category' => 'flavor', 'color' => '#EC4899', 'description' => 'Sweet and dessert-like'],
        ['name' => 'Savory', 'category' => 'flavor', 'color' => '#78716C', 'description' => 'Rich, umami flavors'],
        ['name' => 'Tangy', 'category' => 'flavor', 'color' => '#FBBF24', 'description' => 'Acidic and bright flavors'],
        ['name' => 'Smoky', 'category' => 'flavor', 'color' => '#6B7280', 'description' => 'Smoky, barbecue flavors'],

        // Occasions
        ['name' => 'Holiday', 'category' => 'occasion', 'color' => '#DC2626', 'is_featured' => true, 'description' => 'Perfect for holidays'],
        ['name' => 'Date Night', 'category' => 'occasion', 'color' => '#EC4899', 'description' => 'Romantic dinner recipes'],
        ['name' => 'Party', 'category' => 'occasion', 'color' => '#8B5CF6', 'description' => 'Great for entertaining'],
        ['name' => 'Potluck', 'category' => 'occasion', 'color' => '#059669', 'description' => 'Easy to transport and share'],
        ['name' => 'Weeknight', 'category' => 'occasion', 'color' => '#3B82F6', 'is_featured' => true, 'description' => 'Quick weeknight meals'],

        // Course Types
        ['name' => 'Appetizer', 'category' => 'course', 'color' => '#F59E0B', 'description' => 'Starter course'],
        ['name' => 'Main Course', 'category' => 'course', 'color' => '#DC2626', 'is_featured' => true, 'description' => 'Main dish'],
        ['name' => 'Dessert', 'category' => 'course', 'color' => '#EC4899', 'description' => 'Sweet ending'],
        ['name' => 'Side Dish', 'category' => 'course', 'color' => '#10B981', 'description' => 'Accompanies main course'],

        // Difficulty
        ['name' => 'Beginner', 'category' => 'difficulty', 'color' => '#10B981', 'is_featured' => true, 'description' => 'Easy for beginners'],
        ['name' => 'Intermediate', 'category' => 'difficulty', 'color' => '#F59E0B', 'description' => 'Some cooking experience required'],
        ['name' => 'Advanced', 'category' => 'difficulty', 'color' => '#DC2626', 'description' => 'For experienced cooks'],

        // Time-based
        ['name' => 'Quick', 'category' => 'time', 'color' => '#10B981', 'is_featured' => true, 'description' => 'Ready in 30 minutes or less'],
        ['name' => 'Make-Ahead', 'category' => 'time', 'color' => '#8B5CF6', 'description' => 'Can be prepared in advance'],
        ['name' => 'One-Pot', 'category' => 'time', 'color' => '#F97316', 'description' => 'Minimal cleanup required'],

        // Health & Nutrition
        ['name' => 'High-Protein', 'category' => 'health', 'color' => '#DC2626', 'description' => 'Rich in protein'],
        ['name' => 'Low-Calorie', 'category' => 'health', 'color' => '#10B981', 'description' => 'Lower calorie content'],
        ['name' => 'Heart-Healthy', 'category' => 'health', 'color' => '#EC4899', 'description' => 'Good for heart health'],

        // Seasonal
        ['name' => 'Summer', 'category' => 'season', 'color' => '#FBBF24', 'description' => 'Perfect for summer'],
        ['name' => 'Winter', 'category' => 'season', 'color' => '#3B82F6', 'description' => 'Warming winter dishes'],
        ['name' => 'Fall', 'category' => 'season', 'color' => '#F97316', 'description' => 'Autumn flavors'],
        ['name' => 'Spring', 'category' => 'season', 'color' => '#10B981', 'description' => 'Fresh spring ingredients'],

        // Style
        ['name' => 'Italian', 'category' => 'style', 'color' => '#DC2626', 'description' => 'Italian cuisine'],
        ['name' => 'Mexican', 'category' => 'style', 'color' => '#059669', 'description' => 'Mexican flavors'],
        ['name' => 'Asian', 'category' => 'style', 'color' => '#F59E0B', 'description' => 'Asian-inspired dishes'],
        ['name' => 'Mediterranean', 'category' => 'style', 'color' => '#3B82F6', 'description' => 'Mediterranean diet'],
        ['name' => 'Comfort Food', 'category' => 'style', 'color' => '#92400E', 'description' => 'Hearty, comforting dishes'],
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
                'category' => $tag['category'],
                'description' => $tag['description'] ?? null,
                'color' => $tag['color'],
                'is_dietary_restriction' => $tag['is_dietary_restriction'] ?? false,
                'is_featured' => $tag['is_featured'] ?? false,
                'usage_count' => $this->faker->numberBetween(0, 100),
            ];
        }

        // Fallback to random generation
        $categories = ['dietary', 'cooking-method', 'flavor', 'occasion', 'course', 'difficulty', 'time', 'health', 'season', 'style'];
        $colors = ['#DC2626', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#F97316', '#059669'];
        
        $name = $this->faker->words(2, true);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'category' => $this->faker->randomElement($categories),
            'description' => $this->faker->sentence(),
            'color' => $this->faker->randomElement($colors),
            'is_dietary_restriction' => $this->faker->boolean(20), // 20% chance
            'is_featured' => $this->faker->boolean(15), // 15% chance
            'usage_count' => $this->faker->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the tag is a dietary restriction.
     */
    public function dietaryRestriction(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_dietary_restriction' => true,
            'category' => 'dietary',
            'color' => $this->faker->randomElement(['#10B981', '#059669', '#F59E0B', '#3B82F6']),
        ]);
    }

    /**
     * Indicate that the tag is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'usage_count' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * Indicate that the tag is popular (high usage count).
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_count' => $this->faker->numberBetween(100, 500),
            'is_featured' => true,
        ]);
    }

    /**
     * Create tags for a specific category.
     */
    public function category(string $category): static
    {
        $categoryColors = [
            'dietary' => ['#10B981', '#059669', '#F59E0B'],
            'cooking-method' => ['#F97316', '#FBBF24', '#DC2626'],
            'flavor' => ['#DC2626', '#EC4899', '#78716C'],
            'occasion' => ['#8B5CF6', '#EC4899', '#059669'],
            'course' => ['#F59E0B', '#DC2626', '#10B981'],
            'difficulty' => ['#10B981', '#F59E0B', '#DC2626'],
            'time' => ['#10B981', '#8B5CF6', '#F97316'],
            'health' => ['#DC2626', '#10B981', '#EC4899'],
            'season' => ['#FBBF24', '#3B82F6', '#F97316', '#10B981'],
            'style' => ['#DC2626', '#059669', '#F59E0B', '#3B82F6'],
        ];

        return $this->state(fn (array $attributes) => [
            'category' => $category,
            'color' => $this->faker->randomElement($categoryColors[$category] ?? ['#6B7280']),
        ]);
    }
}
