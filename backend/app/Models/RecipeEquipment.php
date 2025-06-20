<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Recipe Equipment Pivot Model
 * 
 * Represents the relationship between recipes and equipment with sort order.
 * 
 * @property int $id
 * @property int $recipe_id
 * @property int $equipment_id
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Recipe $recipe
 * @property-read Equipment $equipment
 */
class RecipeEquipment extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recipe_id',
        'equipment_id',
        'sort',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'sort' => 'integer',
    ];

    /**
     * Get the recipe that owns this equipment.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the equipment for this recipe.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Scope to get only required equipment.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope to get only optional equipment.
     */
    public function scopeOptional($query)
    {
        return $query->where('is_required', false);
    }

    /**
     * Get equipment with notes if available.
     */
    public function getFormattedEquipmentAttribute(): string
    {
        $formatted = $this->equipment->name;

        if (!$this->is_required) {
            $formatted .= ' (optional)';
        }

        if ($this->notes) {
            $formatted .= ' - ' . $this->notes;
        }

        return $formatted;
    }

    /**
     * Check if equipment has notes.
     */
    public function hasNotes(): bool
    {
        return !empty($this->notes);
    }
}
