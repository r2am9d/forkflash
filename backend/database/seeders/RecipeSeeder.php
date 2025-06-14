<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeImage;
use App\Models\RecipeInstruction;
use App\Models\User;
use Database\Factories\RecipeImageFactory;
use Illuminate\Database\Seeder;

final class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating recipes with instructions and images...');

        // Get existing users to assign recipes to
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('No users found! Please run UserSeeder first.');

            return;
        }

        $this->command->info(sprintf('Found %d users. Creating recipes...', $users->count()));

        // Create 20 recipes with complete data
        Recipe::factory(20)
            ->recycle($users) // Use existing users instead of creating new ones
            ->create()
            ->each(function (Recipe $recipe): void {
                // Create 5-10 instructions per recipe
                $instructionCount = fake()->numberBetween(5, 10);

                for ($i = 1; $i <= $instructionCount; ++$i) {
                    RecipeInstruction::factory()
                        ->step($i)
                        ->create([
                            'recipe_id' => $recipe->id,
                        ]);
                }

                // Create 1-5 images per recipe (first one is primary)
                $imageCount = fake()->numberBetween(1, 5);

                for ($i = 0; $i < $imageCount; ++$i) {
                    RecipeImage::factory()
                        ->when($i === 0, fn ($factory): RecipeImageFactory => $factory->primary())
                        ->order($i)
                        ->create([
                            'recipe_id' => $recipe->id,
                        ]);
                }
            });

        // Create some specific cuisine types for variety
        $cuisines = ['Italian', 'Mexican', 'Asian', 'Mediterranean', 'Indian'];

        foreach ($cuisines as $cuisine) {
            Recipe::factory(3)
                ->cuisine($cuisine)
                ->recycle($users) // Use existing users
                ->create()
                ->each(function (Recipe $recipe): void {
                    // Add instructions and images for cuisine recipes too
                    $instructionCount = fake()->numberBetween(4, 8);

                    for ($i = 1; $i <= $instructionCount; ++$i) {
                        RecipeInstruction::factory()
                            ->step($i)
                            ->create([
                                'recipe_id' => $recipe->id,
                            ]);
                    }

                    // Add primary image
                    RecipeImage::factory()
                        ->primary()
                        ->create([
                            'recipe_id' => $recipe->id,
                        ]);
                });
        }

        // Create some quick recipes
        Recipe::factory(10)
            ->quick()
            ->recycle($users) // Use existing users
            ->create()
            ->each(function (Recipe $recipe): void {
                // Quick recipes have fewer steps
                $instructionCount = fake()->numberBetween(3, 6);

                for ($i = 1; $i <= $instructionCount; ++$i) {
                    RecipeInstruction::factory()
                        ->step($i)
                        ->create([
                            'recipe_id' => $recipe->id,
                        ]);
                }

                // Add primary image
                RecipeImage::factory()
                    ->primary()
                    ->create([
                        'recipe_id' => $recipe->id,
                    ]);
            });

        $this->command->info('Recipe seeding completed!');
        $this->command->info('Created:');
        $this->command->info('- '.Recipe::count().' recipes');
        $this->command->info('- '.RecipeInstruction::count().' instructions');
        $this->command->info('- '.RecipeImage::count().' images');
    }
}
