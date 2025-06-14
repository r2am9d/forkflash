<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CookingUnit;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service for parsing recipe ingredients into structured grocery items.
 */
final class IngredientParser
{
    /**
     * Parse an ingredient string into structured components.
     *
     * Examples:
     * "1 1/2 cups flour" → ['quantity' => 1.5, 'unit' => 'cup', 'name' => 'flour']
     * "2-3 medium onions" → ['quantity' => '2-3', 'unit' => 'medium', 'name' => 'onions']
     * "Salt to taste" → ['quantity' => null, 'unit' => 'to taste', 'name' => 'Salt']
     *
     * @return array<mixed>
     */
    public function parseIngredient(string $ingredient): array
    {
        $ingredient = mb_trim($ingredient);

        // Pattern 1: Quantity + Unit + Name (e.g., "1 1/2 cups flour")
        if (preg_match('/^(\d+(?:\s*[-–]\s*\d+)?(?:\s+\d+\/\d+)?|\d+\/\d+)\s+([a-zA-Z\s]+?)\s+(.+)$/i', $ingredient, $matches)) {
            $quantity = $this->parseQuantity(mb_trim($matches[1]));
            $unitText = mb_trim($matches[2]);
            $name = mb_trim($matches[3]);

            // Verify the unit makes sense
            if ($this->isLikelyUnit($unitText)) {
                $unit = $this->findOrCreateUnit($unitText);

                return [
                    'quantity' => $quantity,
                    'unit_id' => $unit?->id,
                    'name' => $name,
                    'original' => $ingredient,
                ];
            }
        }

        // Pattern 2: Quantity + Name (no unit, e.g., "3 eggs")
        if (preg_match('/^(\d+(?:\s*[-–]\s*\d+)?(?:\s+\d+\/\d+)?|\d+\/\d+)\s+(.+)$/i', $ingredient, $matches)) {
            return [
                'quantity' => $this->parseQuantity(mb_trim($matches[1])),
                'unit_id' => null,
                'name' => mb_trim($matches[2]),
                'original' => $ingredient,
            ];
        }

        // Pattern 3: Special cases (e.g., "Salt to taste", "Pepper as needed")
        if (preg_match('/^(.+?)\s+(to taste|as needed|for seasoning)$/i', $ingredient, $matches)) {
            $unit = $this->findOrCreateUnit($matches[2]);

            return [
                'quantity' => null,
                'unit_id' => $unit?->id,
                'name' => mb_trim($matches[1]),
                'original' => $ingredient,
            ];
        }

        // Fallback: Just the name
        return [
            'quantity' => null,
            'unit_id' => null,
            'name' => $ingredient,
            'original' => $ingredient,
        ];
    }

    /**
     * Parse multiple ingredients from an array.
     *
     * @param  array<mixed>  $ingredients
     * @return array<mixed>
     */
    public function parseIngredients(array $ingredients): array
    {
        return array_map($this->parseIngredient(...), $ingredients);
    }

    /**
     * Convert parsed ingredient to grocery item data.
     *
     * @param  array<mixed>  $parsedIngredient
     * @return array<mixed>
     */
    public function toGroceryItemData(array $parsedIngredient, int $groceryListId, ?int $recipeId = null): array
    {
        return [
            'grocery_list_id' => $groceryListId,
            'name' => $parsedIngredient['name'],
            'quantity' => is_numeric($parsedIngredient['quantity']) ? $parsedIngredient['quantity'] : null,
            'unit_id' => $parsedIngredient['unit_id'] ?? null,
            'recipe_id' => $recipeId,
            'is_checked' => false,
            'metadata' => [
                'parsed_from' => $parsedIngredient['original'],
            ],
        ];
    }

    /**
     * Find or create a unit based on text input.
     */
    private function findOrCreateUnit(string $unitText): ?Unit
    {
        if ($unitText === '' || $unitText === '0') {
            return null;
        }

        $unitText = mb_trim($unitText);
        $standardized = CookingUnit::standardize($unitText);

        // Try to find existing unit by standardized name first
        /** @var Builder<Unit>|Unit|null $unit */
        $unit = Unit::findByName($standardized);

        if (! $unit instanceof Unit) {
            // Create new unit
            return Unit::create([
                'name' => mb_strtolower($standardized),
                'display_name' => ucfirst($standardized),
                'unit_type' => CookingUnit::getCategory($standardized) ?? 'other',
                'is_standardized' => CookingUnit::isStandard($unitText),
                'abbreviation' => $this->getUnitAbbreviation($standardized),
                'description' => sprintf('Standard %s unit', $standardized),
            ]);
        }

        return $unit;
    }

    /**
     * Parse quantity string to decimal or keep as string for ranges.
     */
    private function parseQuantity(string $quantity): float|string|null
    {
        $quantity = mb_trim($quantity);

        // Handle ranges (e.g., "2-3", "1–2")
        if (preg_match('/^(\d+)\s*[-–]\s*(\d+)$/', $quantity)) {
            return $quantity; // Keep ranges as strings
        }

        // Handle fractions (e.g., "1/2", "3/4")
        if (preg_match('/^(\d+)\/(\d+)$/', $quantity, $matches)) {
            return (float) $matches[1] / (float) $matches[2];
        }

        // Handle mixed numbers (e.g., "1 1/2")
        if (preg_match('/^(\d+)\s+(\d+)\/(\d+)$/', $quantity, $matches)) {
            $whole = (float) $matches[1];
            $fraction = (float) $matches[2] / (float) $matches[3];

            return $whole + $fraction;
        }

        // Handle decimal numbers
        if (is_numeric($quantity)) {
            return (float) $quantity;
        }

        return null;
    }

    /**
     * Check if a word is likely a cooking unit.
     */
    private function isLikelyUnit(string $word): bool
    {
        $word = mb_strtolower(mb_trim($word));

        // Check against our standard units
        if (CookingUnit::isStandard($word)) {
            return true;
        }

        // Common unit patterns
        $unitPatterns = [
            '/^(small|medium|large|extra\s+large)$/i',
            '/^(can|jar|bottle|package|bag|box)s?$/i',
            '/^(bunch|bundle|head|clove)s?$/i',
        ];

        foreach ($unitPatterns as $pattern) {
            if (preg_match($pattern, $word)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get abbreviation for a unit name.
     */
    private function getUnitAbbreviation(string $unit): ?string
    {
        $abbreviations = [
            'tablespoon' => 'tbsp',
            'tablespoons' => 'tbsp',
            'teaspoon' => 'tsp',
            'teaspoons' => 'tsp',
            'fluid ounce' => 'fl oz',
            'fluid ounces' => 'fl oz',
            'pound' => 'lb',
            'pounds' => 'lb',
            'ounce' => 'oz',
            'ounces' => 'oz',
            'gram' => 'g',
            'grams' => 'g',
            'kilogram' => 'kg',
            'kilograms' => 'kg',
            'milliliter' => 'ml',
            'milliliters' => 'ml',
            'liter' => 'l',
            'liters' => 'l',
            'package' => 'pkg',
            'packages' => 'pkg',
            'small' => 'sm',
            'medium' => 'med',
            'large' => 'lg',
            'extra large' => 'xl',
        ];

        return $abbreviations[mb_strtolower($unit)] ?? null;
    }
}
