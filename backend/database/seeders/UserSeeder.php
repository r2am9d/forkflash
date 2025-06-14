<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating users...');

        // Create a test user for development
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
        ]);

        // Create an admin user for testing
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);

        // Create additional regular users for recipe diversity
        $userCount = 10;
        User::factory($userCount)->create();

        $this->command->info('Created '.User::count().' users total.');
    }
}
