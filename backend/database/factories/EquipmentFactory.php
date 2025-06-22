<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Equipment>
 */
final class EquipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Equipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $equipmentData = $this->getRandomEquipment();

        return [
            'name' => $equipmentData['name'],
            'slug' => Str::slug($equipmentData['name']),
        ];
    }

    /**
     * Get random equipment data from predefined list.
     *
     * @return array<mixed>
     */
    private function getRandomEquipment(): array
    {
        $equipmentList = [
            // Common cooking equipment that matches JSON data
            ['name' => 'Air Fryer'],
            ['name' => 'Grill'],
            ['name' => 'Oven'],
            ['name' => 'Stovetop'],
            ['name' => 'Microwave'],
            ['name' => 'Slow Cooker'],
            ['name' => 'Pressure Cooker'],
            ['name' => 'Stand Mixer'],
            ['name' => 'Blender'],
            ['name' => 'Food Processor'],
            ['name' => 'Hand Mixer'],
            ['name' => 'Skillet'],
            ['name' => 'Saucepan'],
            ['name' => 'Baking Sheet'],
            ['name' => 'Dutch Oven'],
            ['name' => 'Wok'],
            ['name' => 'Cast Iron Skillet'],
            ['name' => 'Steamer'],
            ['name' => 'Rice Cooker'],
            ['name' => 'Toaster Oven'],
            ['name' => 'Immersion Blender'],
            ['name' => 'Instant Pot'],
            ['name' => 'Bread Machine'],
            ['name' => 'Deep Fryer'],
        ];

        return $this->faker->randomElement($equipmentList);
    }
}
