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
 * @property string|null $difficulty_level
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
        'difficulty_level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'servings' => 'integer',
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
     * @return HasMany<Instruction, $this>
     */
    public function instructions(): HasMany
    {
        return $this->hasMany(Instruction::class)->orderBy('sort');
    }

    /**
     * Get the images for the recipe.
     *
     * @return HasMany<Image, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class)->orderBy('sort');
    }

    /**
     * Get the primary image for the recipe.
     *
     * @return HasMany<Image, $this>
     */
    public function primaryImage(): HasMany
    {
        return $this->hasMany(Image::class)->where('is_primary', true);
    }

    /**
     * Get the ingredients for this recipe (many-to-many relationship).
     *
     * @return BelongsToMany<Ingredient, $this>
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['display_text', 'sort'])
            ->orderByPivot('sort');
    }

    /**
     * Get the equipments for this recipe (many-to-many relationship).
     *
     * @return BelongsToMany<Equipment, $this>
     */
    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'recipe_equipments')
            ->withPivot(['sort'])
            ->orderByPivot('sort');
    }

    /**
     * Get the duration information for this recipe.
     *
     * @return HasOne<Duration, $this>
     */
    public function duration(): HasOne
    {
        return $this->hasOne(Duration::class);
    }

    /**
     * Get the nutrition information for this recipe (many-to-many with nutrients).
     * Access pivot data: $recipe->nutrients->first()->pivot->amount
     *
     * @return BelongsToMany<Nutrient, $this>
     */
    public function nutrients(): BelongsToMany
    {
        return $this->belongsToMany(Nutrient::class, 'recipe_nutrients')
            ->withPivot(['amount', 'percentage_dv']);
    }

    /**
     * Get the tricks for this recipe (consolidates notes and tips).
     *
     * @return HasMany<Trick, $this>
     */
    public function tricks(): HasMany
    {
        return $this->hasMany(Trick::class)->orderBy('sort');
    }

    /**
     * Get the reactions for this recipe.
     *
     * @return HasMany<Reaction, $this>
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class)->orderBy('created_at', 'desc');
    }
}
