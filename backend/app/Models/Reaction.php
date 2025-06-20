<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Reaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'recipe_reactions';

    protected $fillable = [
        'recipe_id',
        'user_id',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    // Relationship: Reaction belongs to Recipe
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    // Relationship: Reaction belongs to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for filtering reactions
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    // Helper methods
    public function isEngagementReaction(): bool
    {
        return in_array($this->type, ['bookmark', 'tried']);
    }

    public function isPositiveReaction(): bool
    {
        return in_array($this->type, ['like', 'love', 'tried']);
    }

    // Get reaction emoji representation
    public function getEmojiAttribute(): string
    {
        return match($this->type) {
            'like' => '👍',
            'love' => '❤️',
            'bookmark' => '🔖',
            'tried' => '✅',
            default => '👍',
        };
    }

    // Get human-readable reaction label
    public function getLabelAttribute(): string
    {
        return match($this->type) {
            'like' => 'Liked',
            'love' => 'Loved',
            'bookmark' => 'Bookmarked',
            'tried' => 'Tried It',
            default => 'Reacted',
        };
    }

    // Available reaction types
    public static function getReactionTypes(): array
    {
        return [
            'like' => ['emoji' => '👍', 'label' => 'Like'],
            'love' => ['emoji' => '❤️', 'label' => 'Love'],
            'bookmark' => ['emoji' => '🔖', 'label' => 'Bookmark'],
            'tried' => ['emoji' => '✅', 'label' => 'Tried It'],
        ];
    }
}
