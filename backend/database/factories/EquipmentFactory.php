<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
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
            'category' => $equipmentData['category'],
            'is_essential' => $equipmentData['is_essential'],
            'alternatives' => $equipmentData['alternatives'],
            'description' => $equipmentData['description'],
            'average_price' => $equipmentData['average_price'],
        ];
    }

    /**
     * Get random equipment data from predefined list.
     */
    private function getRandomEquipment(): array
    {
        $equipmentList = [
            // Essential Kitchen Equipment
            [
                'name' => 'Chef\'s Knife',
                'category' => 'knives',
                'is_essential' => true,
                'alternatives' => ['Santoku knife', 'Utility knife'],
                'description' => '8-10 inch chef\'s knife for chopping, dicing, and slicing',
                'average_price' => 75.00,
            ],
            [
                'name' => 'Cutting Board',
                'category' => 'prep',
                'is_essential' => true,
                'alternatives' => ['Wooden cutting board', 'Bamboo cutting board'],
                'description' => 'Large cutting board for food preparation',
                'average_price' => 25.00,
            ],
            [
                'name' => 'Large Skillet',
                'category' => 'stovetop',
                'is_essential' => true,
                'alternatives' => ['Cast iron skillet', 'Stainless steel pan'],
                'description' => '10-12 inch non-stick or stainless steel skillet',
                'average_price' => 45.00,
            ],
            [
                'name' => 'Saucepan',
                'category' => 'stovetop',
                'is_essential' => true,
                'alternatives' => ['Small pot', '2-quart pot'],
                'description' => '2-3 quart saucepan with lid',
                'average_price' => 35.00,
            ],
            [
                'name' => 'Mixing Bowls',
                'category' => 'prep',
                'is_essential' => true,
                'alternatives' => ['Glass bowls', 'Ceramic bowls'],
                'description' => 'Set of various sized mixing bowls',
                'average_price' => 30.00,
            ],

            // Baking Equipment
            [
                'name' => 'Stand Mixer',
                'category' => 'baking',
                'is_essential' => false,
                'alternatives' => ['Hand mixer', 'Wire whisk'],
                'description' => 'Electric stand mixer for baking and mixing',
                'average_price' => 299.00,
            ],
            [
                'name' => 'Baking Sheet',
                'category' => 'baking',
                'is_essential' => false,
                'alternatives' => ['Cookie sheet', 'Half sheet pan'],
                'description' => 'Rimmed baking sheet for cookies and roasting',
                'average_price' => 20.00,
            ],
            [
                'name' => 'Loaf Pan',
                'category' => 'baking',
                'is_essential' => false,
                'alternatives' => ['9x5 inch pan', 'Glass loaf dish'],
                'description' => 'Standard 9x5 inch loaf pan for bread and cakes',
                'average_price' => 15.00,
            ],

            // Small Appliances
            [
                'name' => 'Food Processor',
                'category' => 'small-appliance',
                'is_essential' => false,
                'alternatives' => ['Blender', 'Chopping by hand'],
                'description' => 'Electric food processor for chopping and mixing',
                'average_price' => 120.00,
            ],
            [
                'name' => 'Blender',
                'category' => 'small-appliance',
                'is_essential' => false,
                'alternatives' => ['Immersion blender', 'Food processor'],
                'description' => 'High-speed blender for smoothies and soups',
                'average_price' => 85.00,
            ],
            [
                'name' => 'Slow Cooker',
                'category' => 'small-appliance',
                'is_essential' => false,
                'alternatives' => ['Dutch oven', 'Pressure cooker'],
                'description' => '6-quart slow cooker for hands-off cooking',
                'average_price' => 55.00,
            ],

            // Specialized Tools
            [
                'name' => 'Kitchen Scale',
                'category' => 'measuring',
                'is_essential' => false,
                'alternatives' => ['Measuring cups', 'Volume measurements'],
                'description' => 'Digital kitchen scale for precise measurements',
                'average_price' => 25.00,
            ],
            [
                'name' => 'Cast Iron Skillet',
                'category' => 'stovetop',
                'is_essential' => false,
                'alternatives' => ['Stainless steel skillet', 'Carbon steel pan'],
                'description' => '10-12 inch cast iron skillet for searing and baking',
                'average_price' => 35.00,
            ],
            [
                'name' => 'Dutch Oven',
                'category' => 'stovetop',
                'is_essential' => false,
                'alternatives' => ['Large pot with lid', 'Slow cooker'],
                'description' => '5-7 quart enameled cast iron Dutch oven',
                'average_price' => 65.00,
            ],
        ];

        return $this->faker->randomElement($equipmentList);
    }

    /**
     * Create equipment marked as essential.
     */
    public function essential(): static
    {
        return $this->state(function (array $attributes) {
            $essentialEquipment = [
                'Chef\'s Knife',
                'Cutting Board', 
                'Large Skillet',
                'Saucepan',
                'Mixing Bowls',
            ];

            $name = $this->faker->randomElement($essentialEquipment);
            
            return [
                'name' => $name,
                'slug' => Str::slug($name),
                'is_essential' => true,
                'category' => 'essential',
            ];
        });
    }

    /**
     * Create baking equipment.
     */
    public function baking(): static
    {
        return $this->state(function (array $attributes) {
            $bakingEquipment = [
                'Stand Mixer',
                'Baking Sheet',
                'Loaf Pan',
                'Muffin Tin',
                'Wire Cooling Rack',
                'Rolling Pin',
            ];

            $name = $this->faker->randomElement($bakingEquipment);
            
            return [
                'name' => $name,
                'slug' => Str::slug($name),
                'category' => 'baking',
                'is_essential' => false,
            ];
        });
    }

    /**
     * Create small appliances.
     */
    public function smallAppliance(): static
    {
        return $this->state(function (array $attributes) {
            $appliances = [
                'Food Processor',
                'Blender',
                'Slow Cooker',
                'Instant Pot',
                'Immersion Blender',
                'Electric Mixer',
            ];

            $name = $this->faker->randomElement($appliances);
            
            return [
                'name' => $name,
                'slug' => Str::slug($name),
                'category' => 'small-appliance',
                'is_essential' => false,
                'average_price' => $this->faker->randomFloat(2, 30, 300),
            ];
        });
    }
}
