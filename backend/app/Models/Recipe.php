<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlids;
use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $name
 * @property string|null $url
 * @property string|null $image
 * @property string|null $summary
 * @property string|null $servings
 * @property array<mixed>|null $info
 * @property array<mixed> $ingredients
 * @property array<mixed>|null $equipments
 * @property array<mixed>|null $notes
 * @property array<mixed>|null $nutrition
 * @property array<mixed>|null $tips
 * @property string|null $video
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Pivot $pivot
 */
final class Recipe extends Model
{
    /** @use HasFactory<RecipeFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'url',
        'image',
        'summary',
        'servings',
        'info',
        'ingredients',
        'equipments',
        'notes',
        'nutrition',
        'tips',
        'video',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'info' => 'array',
        'ingredients' => 'array',
        'equipments' => 'array',
        'notes' => 'array',
        'nutrition' => 'array',
        'tips' => 'array',
    ];

    /**
     * Get the user that owns the recipe.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instructions for the recipe.
     *
     * @return HasMany<RecipeInstruction, $this>
     */
    public function instructions(): HasMany
    {
        return $this->hasMany(RecipeInstruction::class)->orderBy('step_number');
    }

    /**
     * Get the images for the recipe.
     *
     * @return HasMany<RecipeImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(RecipeImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image for the recipe.
     *
     * @return HasMany<RecipeImage, $this>
     */
    public function primaryImage(): HasMany
    {
        return $this->hasMany(RecipeImage::class)->where('is_primary', true);
    }

    /**
     * Get the grocery lists that include this recipe.
     *
     * @return BelongsToMany<GroceryList, $this>
     */
    public function groceryLists(): BelongsToMany
    {
        return $this->belongsToMany(GroceryList::class, 'grocery_list_recipes')
            ->withPivot(['servings', 'selected_item_ids', 'auto_generated'])
            ->withTimestamps()
            ->withCasts([
                'selected_item_ids' => 'array',
            ]);
    }

    /**
     * Get grocery items generated from this recipe.
     *
     * @return HasMany<GroceryItem, $this>
     */
    public function groceryItems(): HasMany
    {
        return $this->hasMany(GroceryItem::class);
    }

    /**
     * Get the prep time from info array.
     */
    public function getPrepTimeAttribute(): ?string
    {
        if (! $this->info) {
            return null;
        }

        foreach ($this->info as $item) {
            if (str_contains(mb_strtolower((string) $item), 'prep')) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Get the cook time from info array.
     */
    public function getCookTimeAttribute(): ?string
    {
        if (! $this->info) {
            return null;
        }

        foreach ($this->info as $item) {
            if (str_contains(mb_strtolower((string) $item), 'cook')) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Get the total time from info array.
     */
    public function getTotalTimeAttribute(): ?string
    {
        if (! $this->info) {
            return null;
        }

        foreach ($this->info as $item) {
            if (str_contains(mb_strtolower((string) $item), 'total')) {
                return $item;
            }
        }

        return null;
    }
}
