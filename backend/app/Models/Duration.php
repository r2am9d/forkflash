<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Recipe $recipe
 */
final class Duration extends Model
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
     *
     * @return BelongsTo<Recipe, $this>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Scope to get recipes within a total time range.
     *
     * @param  mixed  $query
     */
    public function scopeWithinTotalTime($query, int $maxMinutes): mixed
    {
        return $query->where('total_minutes', '<=', $maxMinutes);
    }

    /**
     * Scope to get quick recipes (30 minutes or less total time).
     *
     * @param  mixed  $query
     */
    public function scopeQuick($query): mixed
    {
        return $query->where('total_minutes', '<=', 30);
    }

    /**
     * Scope to get recipes with quick prep time (15 minutes or less).
     *
     * @param  mixed  $query
     */
    public function scopeQuickPrep($query): mixed
    {
        return $query->where('prep_minutes', '<=', 15);
    }
}
