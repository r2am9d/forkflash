<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RecipeReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'user_id',
        'reaction_type',
        'comment',
        'rating',
        'metadata',
        'is_public',
        'reacted_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'reacted_at' => 'datetime',
        'rating' => 'integer',
    ];

    protected $dates = [
        'reacted_at',
    ];

    // Relationship: RecipeReaction belongs to Recipe
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    // Relationship: RecipeReaction belongs to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for filtering reactions
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('reaction_type', $type);
    }

    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('reacted_at', '>=', Carbon::now()->subDays($days));
    }

    // Helper methods
    public function isPositiveReaction(): bool
    {
        return in_array($this->reaction_type, ['like', 'love', 'helpful', 'tried_it']);
    }

    public function isEngagementReaction(): bool
    {
        return in_array($this->reaction_type, ['want_to_try', 'bookmarked', 'shared']);
    }

    public function hasComment(): bool
    {
        return !empty($this->comment);
    }

    public function hasRating(): bool
    {
        return !is_null($this->rating);
    }

    // Get reaction emoji representation
    public function getEmojiAttribute(): string
    {
        return match($this->reaction_type) {
            'like' => '👍',
            'love' => '❤️',
            'wow' => '😮',
            'helpful' => '💡',
            'tried_it' => '✅',
            'want_to_try' => '🤔',
            'bookmarked' => '🔖',
            'shared' => '📤',
            default => '👍',
        };
    }

    // Get human-readable reaction label
    public function getLabelAttribute(): string
    {
        return match($this->reaction_type) {
            'like' => 'Liked',
            'love' => 'Loved',
            'wow' => 'Amazing',
            'helpful' => 'Helpful',
            'tried_it' => 'Tried It',
            'want_to_try' => 'Want to Try',
            'bookmarked' => 'Bookmarked',
            'shared' => 'Shared',
            default => 'Reacted',
        };
    }

    // Available reaction types
    public static function getReactionTypes(): array
    {
        return [
            'like' => ['emoji' => '👍', 'label' => 'Like'],
            'love' => ['emoji' => '❤️', 'label' => 'Love'],
            'wow' => ['emoji' => '😮', 'label' => 'Wow'],
            'helpful' => ['emoji' => '💡', 'label' => 'Helpful'],
            'tried_it' => ['emoji' => '✅', 'label' => 'Tried It'],
            'want_to_try' => ['emoji' => '🤔', 'label' => 'Want to Try'],
            'bookmarked' => ['emoji' => '🔖', 'label' => 'Bookmark'],
            'shared' => ['emoji' => '📤', 'label' => 'Share'],
        ];
    }
}
