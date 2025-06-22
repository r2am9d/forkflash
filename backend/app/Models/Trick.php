<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Trick Model
 *
 * Stores cooking tips, and notes for recipes.
 * Consolidates both "tips" and "notes" from JSON data into one flexible table.
 *
 * @property int $id
 * @property int $recipe_id
 * @property string $content
 * @property string $type
 * @property int $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Recipe $recipe
 */
final class Trick extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'recipe_tricks';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recipe_id',
        'content',
        'type',
        'sort',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'sort' => 'integer',
    ];

    /**
     * Get the recipe that owns this trick.
     *
     * @return BelongsTo<Recipe, $this>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Scope to get only tricks.
     *
     * @param  mixed  $query
     */
    public function scopeTricks($query): mixed
    {
        return $query->where('type', 'trick');
    }

    /**
     * Scope to get only tips.
     *
     * @param  mixed  $query
     */
    public function scopeTips($query): mixed
    {
        return $query->where('type', 'tip');
    }

    /**
     * Scope to get only notes.
     *
     * @param  mixed  $query
     */
    public function scopeNotes($query): mixed
    {
        return $query->where('type', 'note');
    }

    /**
     * Scope to get content ordered by sort.
     *
     * @param  mixed  $query
     */
    public function scopeOrdered($query): mixed
    {
        return $query->orderBy('sort');
    }
}
