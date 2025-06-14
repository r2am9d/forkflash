<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GroceryList;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

final class GroceryListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating grocery lists...');

        // Get existing users and recipes
        $users = User::all();
        $recipes = Recipe::all();

        if ($users->isEmpty()) {
            $this->command->error('No users found! Please run UserSeeder first.');

            return;
        }

        if ($recipes->isEmpty()) {
            $this->command->error('No recipes found! Please run RecipeSeeder first.');

            return;
        }

        $this->command->info(sprintf('Found %d users and %d recipes. Creating grocery lists...', $users->count(), $recipes->count()));

        // Create grocery lists for each user
        $users->each(function (User $user) use ($recipes): void {
            // Create 2-4 grocery lists per user
            $listCount = fake()->numberBetween(2, 4);

            for ($i = 0; $i < $listCount; ++$i) {
                $groceryList = GroceryList::factory()
                    ->forUser($user)
                    ->create();

                // 60% chance to attach 1-3 recipes to the list
                if (fake()->boolean(60)) {
                    $selectedRecipes = $recipes->random(fake()->numberBetween(1, 3));

                    foreach ($selectedRecipes as $recipe) {
                        $groceryList->recipes()->attach($recipe->id, [
                            'servings' => fake()->numberBetween(2, 6),
                            'selected_item_ids' => json_encode([]), // Will be populated by observer when items are created
                            'auto_generated' => true,
                        ]);
                    }
                }
            }
        });

        // Create some template lists (shared across users)
        $this->createTemplateLists();

        // Create some shared lists
        $this->createSharedLists($users);

        $this->command->info('Grocery list seeding completed!');
        $this->command->info('Created:');
        $this->command->info('- '.GroceryList::count().' grocery lists');
        $this->command->info('- '.GroceryList::templates()->count().' template lists');
        $this->command->info('- '.GroceryList::where('is_shared', true)->count().' shared lists');
    }

    /**
     * Create template grocery lists.
     */
    private function createTemplateLists(): void
    {
        $templates = [
            [
                'name' => 'Template: Weekly Essentials',
                'description' => 'Basic weekly grocery shopping template',
                'tags' => ['weekly', 'essentials', 'basic'],
            ],
            [
                'name' => 'Template: Party Planning',
                'description' => 'Template for hosting parties and gatherings',
                'tags' => ['party', 'entertaining', 'social'],
            ],
            [
                'name' => 'Template: Healthy Meal Prep',
                'description' => 'Template for healthy meal preparation',
                'tags' => ['healthy', 'meal-prep', 'nutrition'],
            ],
            [
                'name' => 'Template: Quick Dinners',
                'description' => 'Template for quick weeknight dinner ingredients',
                'tags' => ['quick', 'dinner', 'weeknight'],
            ],
            [
                'name' => 'Template: Breakfast Staples',
                'description' => 'Template for breakfast essentials',
                'tags' => ['breakfast', 'morning', 'staples'],
            ],
        ];

        foreach ($templates as $templateData) {
            GroceryList::factory()
                ->template()
                ->forUser(User::first()) // Assign to first user as creator
                ->create($templateData);
        }
    }

    /**
     * Create shared grocery lists.
     *
     * @param  Collection<int, User>  $users
     */
    private function createSharedLists($users): void
    {
        // Create some shared lists between users
        $sharedLists = [
            [
                'name' => 'Family Weekly Shopping',
                'description' => 'Shared weekly shopping list for the family',
                'tags' => ['family', 'weekly', 'shared'],
            ],
            [
                'name' => 'Roommate Groceries',
                'description' => 'Shared grocery list for roommates',
                'tags' => ['roommates', 'shared', 'household'],
            ],
            [
                'name' => 'Office Potluck',
                'description' => 'Shared list for office potluck event',
                'tags' => ['office', 'potluck', 'work'],
            ],
        ];

        foreach ($sharedLists as $sharedData) {
            $owner = $users->random();
            $sharedWith = $users->except([$owner->id])->random(fake()->numberBetween(1, 3))->pluck('id')->toArray();

            GroceryList::factory()
                ->forUser($owner)
                ->shared()
                ->create(array_merge($sharedData, [
                    'shared_with' => $sharedWith,
                ]));
        }
    }
}
