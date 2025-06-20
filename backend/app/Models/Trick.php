<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Recipe $recipe
 */
class Trick extends Model
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
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Scope to get only tricks.
     */
    public function scopeTricks($query)
    {
        return $query->where('type', 'trick');
    }

    /**
     * Scope to get only tips.
     */
    public function scopeTips($query)
    {
        return $query->where('type', 'tip');
    }

    /**
     * Scope to get only notes.
     */
    public function scopeNotes($query)
    {
        return $query->where('type', 'note');
    }

    /**
     * Scope to get content ordered by sort.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort');
    }
}
