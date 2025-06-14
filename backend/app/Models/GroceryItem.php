<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlids;
use Database\Factories\GroceryItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ulid
 * @property int $grocery_list_id
 * @property string $name
 * @property string|null $category
 * @property float|null $quantity
 * @property int|null $unit_id
 * @property string|null $notes
 * @property bool $is_checked
 * @property Carbon|null $checked_at
 * @property int $sort_order
 * @property float|null $estimated_price
 * @property int|null $recipe_id
 * @property array<mixed> $metadata
 * @property-read GroceryList $groceryList
 * @property-read Recipe|null $recipe
 * @property-read Unit|null $unit
 */
final class GroceryItem extends Model
{
    /** @use HasFactory<GroceryItemFactory> */
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
        'category',
        'quantity',
        'unit_id',
        'notes',
        'is_checked',
        'checked_at',
        'sort_order',
        'estimated_price',
        'recipe_id',
        'metadata',
    ];

    /**
     * Get the grocery list that owns this item.
     *
     * @return BelongsTo<GroceryList, $this>
     */
    public function groceryList(): BelongsTo
    {
        return $this->belongsTo(GroceryList::class);
    }

    /**
     * Get the recipe this item was generated from (if any).
     *
     * @return BelongsTo<Recipe, $this>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the unit for this item.
     *
     * @return BelongsTo<Unit, $this>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Check the item.
     */
    public function check(): bool
    {
        return $this->update([
            'is_checked' => true,
            'checked_at' => now(),
        ]);
    }

    /**
     * Uncheck the item.
     */
    public function uncheck(): bool
    {
        return $this->update([
            'is_checked' => false,
            'checked_at' => null,
        ]);
    }

    /**
     * Toggle the checked state.
     */
    public function toggle(): bool
    {
        return $this->is_checked ? $this->uncheck() : $this->check();
    }

    /**
     * Get the display quantity with unit.
     */
    public function getDisplayQuantityAttribute(): string
    {
        if (! $this->quantity) {
            return $this->unit->display ?? '';
        }

        $quantity = $this->quantity;
        $unitName = $this->unit->display ?? '';

        // Remove unnecessary decimals
        if ($quantity === floor($quantity)) {
            $quantity = (int) $quantity;
        }

        return $unitName ? sprintf('%s %s', $quantity, $unitName) : (string) $quantity;
    }

    /**
     * Check if unit is standardized.
     */
    public function hasStandardUnit(): bool
    {
        return $this->unit->is_standardized ?? false;
    }

    /**
     * Get unit category (volume, weight, count, special).
     */
    public function getUnitCategory(): string
    {
        return $this->unit->unit_type;
    }

    /**
     * Check if item was generated from a recipe.
     */
    public function isFromRecipe(): bool
    {
        return $this->recipe_id !== null;
    }

    /**
     * Scope for checked items.
     *
     * @param  mixed  $query
     */
    public function scopeChecked($query): mixed
    {
        return $query->where('is_checked', true);
    }

    /**
     * Scope for unchecked items.
     *
     * @param  mixed  $query
     */
    public function scopeUnchecked($query): mixed
    {
        return $query->where('is_checked', false);
    }

    /**
     * Scope for items by category.
     *
     * @param  mixed  $query
     */
    public function scopeByCategory($query, string $category): mixed
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for items from recipes.
     *
     * @param  mixed  $query
     */
    public function scopeFromRecipe($query): mixed
    {
        return $query->whereNotNull('recipe_id');
    }

    /**
     * Scope for manual items (not from recipes).
     *
     * @param  mixed  $query
     */
    public function scopeManual($query): mixed
    {
        return $query->whereNull('recipe_id');
    }

    /**
     * Scope for ordered items.
     *
     * @param  mixed  $query
     */
    public function scopeOrdered($query): mixed
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'is_checked' => 'boolean',
            'checked_at' => 'datetime',
            'sort_order' => 'integer',
            'estimated_price' => 'decimal:2',
            'metadata' => 'array',
        ];
    }
}
