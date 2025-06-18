<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Recipe Timing Model
 * 
 * Stores structured timing information for recipes including prep time,
 * cook time, difficulty level, and timing guidance.
 * 
 * @property int $id
 * @property int $recipe_id
 * @property int|null $prep_minutes
 * @property int|null $cook_minutes
 * @property int|null $total_minutes
 * @property int|null $hands_on_time
 * @property int|null $passive_time
 * @property string|null $difficulty_level
 * @property float $servings_time_multiplier
 * @property string|null $timing_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Recipe $recipe
 */
class RecipeTiming extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recipe_id',
        'prep_minutes',
        'cook_minutes',
        'total_minutes',
        'hands_on_time',
        'passive_time',
        'difficulty_level',
        'servings_time_multiplier',
        'timing_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'prep_minutes' => 'integer',
        'cook_minutes' => 'integer',
        'total_minutes' => 'integer',
        'hands_on_time' => 'integer',
        'passive_time' => 'integer',
        'servings_time_multiplier' => 'decimal:2',
    ];

    /**
     * Get the recipe that owns this timing information.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Scope to get recipes by difficulty level.
     */
    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Scope to get recipes within a time range.
     */
    public function scopeWithinTotalTime($query, int $maxMinutes)
    {
        return $query->where('total_minutes', '<=', $maxMinutes);
    }

    /**
     * Scope to get quick recipes (30 minutes or less).
     */
    public function scopeQuick($query)
    {
        return $query->where('total_minutes', '<=', 30);
    }

    /**
     * Scope to get easy recipes.
     */
    public function scopeEasy($query)
    {
        return $query->where('difficulty_level', 'easy');
    }

    /**
     * Get formatted total time string.
     */
    public function getFormattedTotalTimeAttribute(): string
    {
        if (!$this->total_minutes) {
            return 'Time not specified';
        }

        $hours = intdiv($this->total_minutes, 60);
        $minutes = $this->total_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return $hours . 'h ' . $minutes . 'm';
        } elseif ($hours > 0) {
            return $hours . 'h';
        } else {
            return $minutes . 'm';
        }
    }

    /**
     * Get formatted prep time string.
     */
    public function getFormattedPrepTimeAttribute(): string
    {
        if (!$this->prep_minutes) {
            return 'Not specified';
        }

        return $this->prep_minutes . ' min prep';
    }

    /**
     * Get formatted cook time string.
     */
    public function getFormattedCookTimeAttribute(): string
    {
        if (!$this->cook_minutes) {
            return 'Not specified';
        }

        return $this->cook_minutes . ' min cook';
    }

    /**
     * Calculate estimated time for different serving sizes.
     */
    public function getEstimatedTimeForServings(int $targetServings, int $originalServings = 4): int
    {
        if (!$this->total_minutes || $originalServings <= 0) {
            return $this->total_minutes ?? 0;
        }

        $ratio = $targetServings / $originalServings;
        $adjustedTime = $this->total_minutes * ($ratio ** $this->servings_time_multiplier);

        return (int) round($adjustedTime);
    }

    /**
     * Check if this is a quick recipe (30 minutes or less).
     */
    public function isQuick(): bool
    {
        return $this->total_minutes && $this->total_minutes <= 30;
    }

    /**
     * Check if this is a slow recipe (over 2 hours).
     */
    public function isSlow(): bool
    {
        return $this->total_minutes && $this->total_minutes > 120;
    }

    /**
     * Get timing breakdown array.
     */
    public function getTimingBreakdownAttribute(): array
    {
        return [
            'prep' => $this->prep_minutes,
            'cook' => $this->cook_minutes,
            'hands_on' => $this->hands_on_time,
            'passive' => $this->passive_time,
            'total' => $this->total_minutes,
            'difficulty' => $this->difficulty_level,
        ];
    }
}
