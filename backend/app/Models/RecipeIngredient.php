<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Recipe Ingredient Pivot Model
 * 
 * Represents the relationship between recipes and ingredients with quantity,
 * units, preparation notes, and ordering information.
 * 
 * @property int $id
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property float|null $quantity
 * @property int|null $unit_id
 * @property string|null $notes
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Recipe $recipe
 * @property-read Ingredient $ingredient
 * @property-read Unit|null $unit
 */
class RecipeIngredient extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'quantity',
        'unit_id',
        'notes',
        'sort',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity' => 'decimal:2',
        'sort' => 'integer',
    ];

    /**
     * Get the recipe that owns this ingredient.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the ingredient for this recipe.
     */
    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Get the unit for this ingredient.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Scope to order ingredients by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort');
    }

    /**
     * Scope to get only required ingredients.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_optional', false);
    }

    /**
     * Scope to get only optional ingredients.
     */
    public function scopeOptional($query)
    {
        return $query->where('is_optional', true);
    }

    /**
     * Get formatted quantity with unit.
     */
    public function getFormattedQuantityAttribute(): string
    {
        if (!$this->quantity) {
            return '';
        }

        $formatted = $this->quantity;
        
        if ($this->unit) {
            $formatted .= ' ' . $this->unit->abbreviation ?? $this->unit->display_name;
        }

        return $formatted;
    }

    /**
     * Get full ingredient description with quantity, unit, and preparation.
     */
    public function getFullDescriptionAttribute(): string
    {
        $parts = [];

        if ($this->formatted_quantity) {
            $parts[] = $this->formatted_quantity;
        }

        $parts[] = $this->ingredient->name;

        if ($this->notes) {
            $parts[] = $this->notes;
        }

        return implode(' ', $parts);
    }
}
