<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\GroceryItemObserver;
use App\Traits\HasUlids;
use Database\Factories\GroceryListFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property bool $is_template
 * @property bool $is_shared
 * @property array<int> $shared_with
 * @property array<string> $tags
 * @property Carbon|null $completed_at
 * @property array<mixed> $metadata
 * @property-read User $user
 * @property-read Collection<int, GroceryItem> $items
 * @property-read Collection<int, Recipe> $recipes
 * @property-read Pivot $pivot
 */
final class GroceryList extends Model
{
    /** @use HasFactory<GroceryListFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_template',
        'is_shared',
        'shared_with',
        'tags',
        'completed_at',
        'metadata',
    ];

    /**
     * Get the user that owns the grocery list.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the grocery items for this list.
     *
     * @return HasMany<GroceryItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(GroceryItem::class)->orderBy('sort_order');
    }

    /**
     * Get the recipes associated with this grocery list.
     *
     * @return BelongsToMany<Recipe, $this>
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'grocery_list_recipes')
            ->withPivot(['servings', 'selected_item_ids', 'auto_generated'])
            ->withTimestamps()
            ->withCasts([
                'selected_item_ids' => 'array',
            ]);
    }

    /**
     * Get unchecked items for this list.
     *
     * @return HasMany<GroceryItem, $this>
     */
    public function uncheckedItems(): HasMany
    {
        return $this->items()->where('is_checked', false);
    }

    /**
     * Get checked items for this list.
     *
     * @return HasMany<GroceryItem, $this>
     */
    public function checkedItems(): HasMany
    {
        return $this->items()->where('is_checked', true);
    }

    /**
     * Get items grouped by category.
     *
     * @return Collection<string|int, Collection<int, GroceryItem>>
     */
    public function itemsByCategory(): Collection
    {
        /** @var Collection<int, GroceryItem> $items */
        $items = $this->items()->get();

        return $items->groupBy('category');
    }

    /**
     * Check if the list is completed.
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Mark the list as completed.
     */
    public function markAsCompleted(): bool
    {
        return $this->update(['completed_at' => now()]);
    }

    /**
     * Mark the list as incomplete.
     */
    public function markAsIncomplete(): bool
    {
        return $this->update(['completed_at' => null]);
    }

    /**
     * Get the total number of items.
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->items()->count();
    }

    /**
     * Get the number of checked items.
     */
    public function getCheckedItemsCountAttribute(): int
    {
        return $this->checkedItems()->count();
    }

    /**
     * Get the completion percentage.
     */
    public function getCompletionPercentageAttribute(): float
    {
        $total = $this->total_items;
        if ($total === 0) {
            return 0.0;
        }

        return round(($this->checked_items_count / $total) * 100, 2);
    }

    /**
     * Get estimated total price.
     */
    public function getEstimatedTotalPriceAttribute(): float
    {
        return $this->items()->sum('estimated_price');
    }

    /**
     * Check if user has access to this list.
     */
    public function hasAccess(User $user): bool
    {
        // Owner always has access
        if ($this->user_id === $user->id) {
            return true;
        }

        // Check if shared with user
        if ($this->is_shared && $this->shared_with) {
            /** @var array<int> */
            $data = (array) $this->shared_with;

            return in_array($user->id, $data, true);
        }

        return false;
    }

    /**
     * Scope for lists accessible by user.
     *
     * @param  mixed  $query
     */
    public function scopeAccessibleBy($query, User $user): mixed
    {
        return $query->where(function ($q) use ($user): void {
            $q->where('user_id', $user->id)
                ->orWhere(function ($subQuery) use ($user): void {
                    $subQuery->where('is_shared', true)
                        ->whereJsonContains('shared_with', $user->id);
                });
        });
    }

    /**
     * Scope for template lists.
     *
     * @param  mixed  $query
     */
    public function scopeTemplates($query): mixed
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope for active (non-completed) lists.
     *
     * @param  mixed  $query
     */
    public function scopeActive($query): mixed
    {
        return $query->whereNull('completed_at');
    }

    /**
     * Scope for completed lists.
     *
     * @param  mixed  $query
     */
    public function scopeCompleted($query): mixed
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * Override delete to handle recipe references cleanup.
     */
    public function delete()
    {
        // The observer will handle individual item cleanup automatically
        return parent::delete();
    }

    /**
     * Get selected grocery items for a specific recipe.
     *
     * @return Collection<int, mixed>
     */
    public function getSelectedItemsForRecipe(Recipe $recipe): Collection
    {
        /** @var Recipe|mixed|null $recipe */
        $recipe = $this->recipes()->where('recipe_id', $recipe->id)->first();

        if (! $recipe || ! $recipe->pivot->selected_item_ids) {
            return collect();
        }

        /** @var array<int> $selectedIds */
        $selectedIds = $recipe->pivot->selected_item_ids;

        return $this->items()->whereIn('id', $selectedIds)->get();
    }

    /**
     * Update selected items for a recipe.
     *
     * @param  array<mixed>  $itemIds
     */
    public function updateSelectedItemsForRecipe(Recipe $recipe, array $itemIds): void
    {
        DB::table('grocery_list_recipes')
            ->where('grocery_list_id', $this->id)
            ->where('recipe_id', $recipe->id)
            ->update([
                'selected_item_ids' => json_encode($itemIds),
                'updated_at' => now(),
            ]);
    }

    /**
     * Check if a grocery item is selected for its recipe.
     */
    public function isItemSelectedForRecipe(GroceryItem $item): bool
    {
        if (! $item->recipe_id) {
            return false; // Manual items are always "selected"
        }

        /** @var Recipe|mixed|null $recipe */
        $recipe = $this->recipes()->where('recipe_id', $item->recipe_id)->first();

        if (! $recipe || ! $recipe->pivot->selected_item_ids) {
            return false;
        }

        return in_array($item->id, $recipe->pivot->selected_item_ids);
    }

    /**
     * Get recipe summary with actual selected items data.
     *
     * @return array<mixed>
     */
    public function getRecipeSummary(): array
    {
        /** @var Collection<int, Recipe> $recipes */
        $recipes = $this->recipes;

        return $recipes->map(function ($recipe): array {
            $selectedItems = $this->getSelectedItemsForRecipe($recipe);

            /** @var mixed $pivot */
            $pivot = $recipe->pivot;

            return [
                'recipe_id' => $recipe->id,
                'recipe_name' => $recipe->name,
                'servings' => $pivot->servings,
                'selected_items_count' => $selectedItems->count(),
                'selected_items' => $selectedItems->map(fn ($item): array => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'is_checked' => $item->is_checked,
                ]),
                'total_estimated_cost' => $selectedItems->sum('estimated_price'),
            ];
        })->toArray();
    }

    /**
     * Clean up recipe references when grocery list is deleted.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Handle grocery list deletion
        self::deleting(function ($groceryList): void {
            GroceryItemObserver::handleGroceryListDeletion($groceryList->id);
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_template' => 'boolean',
            'is_shared' => 'boolean',
            'shared_with' => 'array',
            'tags' => 'array',
            'completed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
