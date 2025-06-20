<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Recipe Duration Model
 * 
 * Stores duration information for recipes from JSON data structure:
 * "info": ["Prep: 12 minutes", "Cook: 30 minutes", "Total: 42 minutes"]
 * 
 * @property int $id
 * @property int $recipe_id
 * @property int|null $prep_minutes
 * @property int|null $cook_minutes
 * @property int|null $total_minutes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Recipe $recipe
 */
class Duration extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'recipe_durations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recipe_id',
        'prep_minutes',
        'cook_minutes',
        'total_minutes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'prep_minutes' => 'integer',
        'cook_minutes' => 'integer',
        'total_minutes' => 'integer',
    ];

    /**
     * Get the recipe that owns this timing information.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Scope to get recipes within a total time range.
     */
    public function scopeWithinTotalTime($query, int $maxMinutes)
    {
        return $query->where('total_minutes', '<=', $maxMinutes);
    }

    /**
     * Scope to get quick recipes (30 minutes or less total time).
     */
    public function scopeQuick($query)
    {
        return $query->where('total_minutes', '<=', 30);
    }

    /**
     * Scope to get recipes with quick prep time (15 minutes or less).
     */
    public function scopeQuickPrep($query)
    {
        return $query->where('prep_minutes', '<=', 15);
    }
}
