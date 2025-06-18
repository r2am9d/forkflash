<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Recipe Note Model
 * 
 * Represents individual notes for recipes with categorization and display ordering.
 * Supports different note types: general, dietary, storage, serving.
 * 
 * @property int $id
 * @property int $recipe_id
 * @property string $note_text
 * @property string $note_type
 * @property int $display_order
 * @property bool $is_public
 * @property int|null $created_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Recipe $recipe
 * @property-read User|null $createdBy
 */
class RecipeNote extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recipe_id',
        'note_text',
        'note_type',
        'display_order',
        'is_public',
        'created_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_public' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Available note types.
     */
    public const NOTE_TYPES = [
        'general' => 'General',
        'dietary' => 'Dietary Information',
        'storage' => 'Storage Instructions',
        'serving' => 'Serving Suggestions',
    ];

    /**
     * Get the recipe that owns this note.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the user who created this note.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Scope to get notes by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('note_type', $type);
    }

    /**
     * Scope to get public notes only.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('created_at');
    }

    /**
     * Get formatted note type label.
     */
    public function getFormattedTypeAttribute(): string
    {
        return self::NOTE_TYPES[$this->note_type] ?? ucfirst($this->note_type);
    }

    /**
     * Check if note is of a specific type.
     */
    public function isType(string $type): bool
    {
        return $this->note_type === $type;
    }
}
