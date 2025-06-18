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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $name
 * @property string|null $url
 * @property string|null $summary
 * @property int $servings
 * @property string|null $video
 * @property string|null $cuisine_type
 * @property string|null $meal_type
 * @property string|null $difficulty_level
 * @property float $average_rating
 * @property int $total_ratings
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
        'summary',
        'servings',
        'video',
        'cuisine_type',
        'meal_type',
        'difficulty_level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'servings' => 'integer',
        'average_rating' => 'decimal:2',
        'total_ratings' => 'integer',
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
     * Get the ingredients for this recipe with quantities and preparation details.
     *
     * @return HasMany<RecipeIngredient, $this>
     */
    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class)->orderBy('display_order');
    }

    /**
     * Get the ingredients for this recipe (many-to-many relationship).
     *
     * @return BelongsToMany<Ingredient, $this>
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit_id', 'preparation_notes', 'is_optional', 'display_order'])
            ->withTimestamps()
            ->orderByPivot('display_order');
    }

    /**
     * Get the tags for this recipe (many-to-many relationship).
     *
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'recipe_tags')
            ->withTimestamps();
    }

    /**
     * Get the equipments for this recipe (many-to-many relationship).
     *
     * @return BelongsToMany<Equipment, $this>
     */
    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'recipe_equipments')
            ->withPivot(['is_required', 'notes', 'display_order'])
            ->withTimestamps()
            ->orderByPivot('display_order');
    }

    /**
     * Get the timing information for this recipe.
     *
     * @return HasOne<RecipeTiming, $this>
     */
    public function timing(): HasOne
    {
        return $this->hasOne(RecipeTiming::class);
    }

    /**
     * Get the nutrition information for this recipe (many-to-many with nutrients).
     * Access pivot data: $recipe->nutrients->first()->pivot->amount
     * 
     * TODO: Create Nutrient model and recipe_nutrient migration first
     *
     * @return BelongsToMany<Nutrient, $this>
     */
    public function nutrients(): BelongsToMany
    {
        return $this->belongsToMany(Nutrient::class, 'recipe_nutrient')
            ->withPivot(['amount', 'per_serving', 'source', 'confidence_level', 'sort_order', 'is_active', 'verified_at', 'notes'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /**
     * Get the notes for this recipe.
     *
     * @return HasMany<RecipeNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(RecipeNote::class)->orderBy('display_order');
    }

    /**
     * Get the tips for this recipe.
     *
     * @return HasMany<RecipeTip, $this>
     */
    public function tips(): HasMany
    {
        return $this->hasMany(RecipeTip::class)->orderBy('display_order');
    }

    /**
     * Get the reactions for this recipe.
     *
     * @return HasMany<RecipeReaction, $this>
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(RecipeReaction::class)->orderBy('reacted_at', 'desc');
    }
}
