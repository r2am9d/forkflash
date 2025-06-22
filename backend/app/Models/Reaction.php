<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ReactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Reaction extends Model
{
    /** @use HasFactory<ReactionFactory> */
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

    /**
     * Available reaction types
     *
     * @return array<mixed>
     */
    public static function getReactionTypes(): array
    {
        return [
            'like' => ['emoji' => '👍', 'label' => 'Like'],
            'love' => ['emoji' => '❤️', 'label' => 'Love'],
            'bookmark' => ['emoji' => '🔖', 'label' => 'Bookmark'],
            'tried' => ['emoji' => '✅', 'label' => 'Tried It'],
        ];
    }

    /**
     * Relationship: Reaction belongs to Recipe
     *
     * @return BelongsTo<Recipe, $this>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Relationship: Reaction belongs to User
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes for filtering reactions
     *
     * @param  mixed  $query
     */
    public function scopeByType($query, string $type): mixed
    {
        return $query->where('type', $type);
    }

    /**
     * Scopes for filtering recent reactions
     *
     * @param  mixed  $query
     */
    public function scopeRecent($query, int $days = 30): mixed
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
        return match ($this->type) {
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
        return match ($this->type) {
            'like' => 'Liked',
            'love' => 'Loved',
            'bookmark' => 'Bookmarked',
            'tried' => 'Tried It',
            default => 'Reacted',
        };
    }
}
