<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeTip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeTip>
 */
class RecipeTipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = RecipeTip::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipsByCategory = [
            'cooking' => [
                'Don\'t overcook - it will become tough',
                'Let meat rest for 5 minutes before slicing',
                'Taste and adjust seasoning before serving',
                'Cook over medium heat to prevent burning',
                'Stir occasionally to prevent sticking',
                'Use a meat thermometer for perfect doneness',
                'Preheat your pan before adding ingredients',
                'Season in layers for maximum flavor',
                'Use fresh herbs for the best taste',
                'Don\'t overcrowd the pan',
                'Let ingredients come to room temperature first',
                'Brown the meat well for extra flavor',
                'Deglaze the pan with wine or broth',
                'Cook pasta until al dente for best texture',
            ],
            'prep' => [
                'Marinate overnight for maximum flavor',
                'Mise en place - prepare all ingredients first',
                'Chop vegetables uniformly for even cooking',
                'Dry ingredients thoroughly before cooking',
                'Soak beans overnight before cooking',
                'Room temperature ingredients mix better',
                'Use sharp knives for clean cuts',
                'Pat meat dry before seasoning',
                'Prep vegetables in advance to save time',
                'Measure spices before you start cooking',
                'Freeze meat for 30 minutes for easier slicing',
                'Bring dairy to room temperature before baking',
                'Toast spices for deeper flavor',
                'Zest citrus before juicing',
            ],
            'substitution' => [
                'Use Greek yogurt instead of sour cream',
                'Substitute honey for sugar at 3/4 ratio',
                'Buttermilk substitute: milk + lemon juice',
                'Use applesauce to replace oil in baking',
                'Heavy cream substitute: milk + butter',
                'Replace eggs with flax eggs for vegan option',
                'Use nutritional yeast for cheesy flavor',
                'Coconut milk works as dairy substitute',
                'Fresh herbs can replace dried (use 3x amount)',
                'Lemon juice can substitute for white wine',
                'Breadcrumbs substitute: crushed crackers',
                'Use maple syrup instead of corn syrup',
                'Ground turkey works instead of beef',
                'Vegetable broth replaces chicken broth',
            ],
            'storage' => [
                'Cool completely before refrigerating',
                'Store in shallow containers for faster cooling',
                'Use within 3-4 days for best quality',
                'Freeze in portion-sized containers',
                'Label containers with contents and date',
                'Store herbs like flowers in water',
                'Keep potatoes in dark, cool place',
                'Don\'t store tomatoes in the refrigerator',
                'Wrap cheese in parchment, not plastic',
                'Store onions away from potatoes',
                'Keep bananas separate from other fruits',
                'Store flour in airtight containers',
                'Freeze leftover wine in ice cube trays',
                'Store spices in cool, dark places',
            ],
        ];

        $tipCategory = $this->faker->randomElement(array_keys(RecipeTip::TIP_CATEGORIES));
        $tipText = $this->faker->randomElement($tipsByCategory[$tipCategory]);

        return [
            'recipe_id' => Recipe::factory(),
            'tip_text' => $tipText,
            'tip_category' => $tipCategory,
            'sort' => $this->faker->numberBetween(0, 10),
            'is_public' => $this->faker->boolean(95), // 95% public
            'created_by_user_id' => $this->faker->boolean(40) ? User::factory() : null, // 40% have creator
            'helpfulness_score' => $this->faker->numberBetween(0, 50),
            'times_used' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Create a cooking tip.
     */
    public function cooking(): static
    {
        return $this->state(fn(array $attributes) => [
            'tip_category' => 'cooking',
        ]);
    }

    /**
     * Create a preparation tip.
     */
    public function prep(): static
    {
        return $this->state(fn(array $attributes) => [
            'tip_category' => 'prep',
        ]);
    }

    /**
     * Create a substitution tip.
     */
    public function substitution(): static
    {
        return $this->state(fn(array $attributes) => [
            'tip_category' => 'substitution',
        ]);
    }

    /**
     * Create a storage tip.
     */
    public function storage(): static
    {
        return $this->state(fn(array $attributes) => [
            'tip_category' => 'storage',
        ]);
    }

    /**
     * Create a highly helpful tip.
     */
    public function helpful(): static
    {
        return $this->state(fn(array $attributes) => [
            'helpfulness_score' => $this->faker->numberBetween(25, 100),
            'times_used' => $this->faker->numberBetween(20, 200),
        ]);
    }

    /**
     * Create a frequently used tip.
     */
    public function popular(): static
    {
        return $this->state(fn(array $attributes) => [
            'times_used' => $this->faker->numberBetween(50, 500),
            'helpfulness_score' => $this->faker->numberBetween(10, 75),
        ]);
    }

    /**
     * Create a private tip.
     */
    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Create a tip with specific display order.
     */
    public function ordered(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort' => $order,
        ]);
    }
}
