<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\GroceryItem;
use Illuminate\Support\Facades\DB;

final class GroceryItemObserver
{
    /**
     * Handle bulk deletion scenario - when entire grocery list is deleted.
     */
    public static function handleGroceryListDeletion(int $groceryListId): void
    {
        DB::table('grocery_list_recipes')
            ->where('grocery_list_id', $groceryListId)
            ->delete();
    }

    /**
     * Handle recipe detachment - when a recipe is removed from a grocery list.
     */
    public static function handleRecipeDetachment(int $groceryListId, int $recipeId): void
    {
        DB::table('grocery_list_recipes')
            ->where('grocery_list_id', $groceryListId)
            ->where('recipe_id', $recipeId)
            ->delete();
    }

    /**
     * Handle the GroceryItem "created" event.
     */
    public function created(GroceryItem $groceryItem): void
    {
        // Add new grocery item to selected_item_ids if it came from a recipe
        if ($groceryItem->recipe_id) {
            $this->addToSelectedItemIds($groceryItem);
        }
    }

    /**
     * Handle the GroceryItem "deleted" event.
     */
    public function deleted(GroceryItem $groceryItem): void
    {
        // Remove grocery item from selected_item_ids if it came from a recipe
        if ($groceryItem->recipe_id) {
            $this->removeFromSelectedItemIds($groceryItem);
        }
    }

    /**
     * Handle the GroceryItem "forceDeleted" event.
     */
    public function forceDeleted(GroceryItem $groceryItem): void
    {
        // Remove grocery item from selected_item_ids if it came from a recipe
        if ($groceryItem->recipe_id) {
            $this->removeFromSelectedItemIds($groceryItem);
        }
    }

    /**
     * Add newly created grocery item to selected_item_ids in pivot table.
     */
    private function addToSelectedItemIds(GroceryItem $groceryItem): void
    {
        $pivotRecord = DB::table('grocery_list_recipes')
            ->where('grocery_list_id', $groceryItem->grocery_list_id)
            ->where('recipe_id', $groceryItem->recipe_id)
            ->first();

        if (! $pivotRecord) {
            return;
        }

        $selectedItemIds = json_decode($pivotRecord->selected_item_ids ?? '[]', true);

        if (! in_array($groceryItem->id, $selectedItemIds)) {
            $selectedItemIds[] = $groceryItem->id;

            DB::table('grocery_list_recipes')
                ->where('grocery_list_id', $groceryItem->grocery_list_id)
                ->where('recipe_id', $groceryItem->recipe_id)
                ->update([
                    'selected_item_ids' => json_encode($selectedItemIds),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Remove deleted grocery item from selected_item_ids in pivot table.
     */
    private function removeFromSelectedItemIds(GroceryItem $groceryItem): void
    {
        $pivotRecord = DB::table('grocery_list_recipes')
            ->where('grocery_list_id', $groceryItem->grocery_list_id)
            ->where('recipe_id', $groceryItem->recipe_id)
            ->first();

        if (! $pivotRecord || ! $pivotRecord->selected_item_ids) {
            return;
        }

        $selectedItemIds = json_decode((string) $pivotRecord->selected_item_ids, true);

        if (! is_array($selectedItemIds)) {
            return;
        }

        // Remove the item ID
        $selectedItemIds = array_filter($selectedItemIds, fn ($id): bool => $id !== $groceryItem->id);

        // Re-index array
        $selectedItemIds = array_values($selectedItemIds);

        DB::table('grocery_list_recipes')
            ->where('grocery_list_id', $groceryItem->grocery_list_id)
            ->where('recipe_id', $groceryItem->recipe_id)
            ->update([
                'selected_item_ids' => json_encode($selectedItemIds),
                'updated_at' => now(),
            ]);
    }
}
