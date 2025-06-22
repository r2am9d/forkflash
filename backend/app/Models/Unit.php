<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $unit_type
 * @property bool $is_standardized
 * @property float|null $conversion_factor
 * @property string|null $abbreviation
 *
 * @method static Builder|Unit groupedByType()
 * @method static Builder|Unit findByName(string $name)
 */
final class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'display_name',
        'unit_type',
        'is_standardized',
        'conversion_factor',
        'abbreviation',
    ];

    /**
     * Get units grouped by type.
     *
     * @return array<mixed>
     */
    public static function groupedByType(): array
    {
        return self::orderBy('unit_type')
            ->orderBy('name')
            ->get()
            ->groupBy('unit_type')
            ->toArray();
    }

    /**
     * Find unit by name (case-insensitive).
     */
    public static function findByName(string $name): ?self
    {
        return self::where('name', mb_strtolower(mb_trim($name)))->first();
    }

    /**
     * Scope for standardized units.
     *
     * @param  mixed  $query
     */
    public function scopeStandardized($query): mixed
    {
        return $query->where('is_standardized', true);
    }

    /**
     * Scope for units by type.
     *
     * @param  mixed  $query
     */
    public function scopeByType($query, string $type): mixed
    {
        return $query->where('unit_type', $type);
    }

    /**
     * Get display name or fallback to name.
     */
    public function getDisplayAttribute(): string
    {
        return $this->display_name ?: $this->name;
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_standardized' => 'boolean',
            'conversion_factor' => 'decimal:6',
        ];
    }
}
