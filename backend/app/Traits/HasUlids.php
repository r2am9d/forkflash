<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Custom HasUlids trait that generates ULIDs for a separate 'ulid' column
 * while keeping the auto-incrementing 'id' as the primary key.
 */
trait HasUlids
{
    /**
     * Find a model by ULID.
     */
    public static function findByUlid(string $ulid): mixed
    {
        return static::whereUlid($ulid)->first();
    }

    /**
     * Find a model by ULID or fail.
     */
    public static function findByUlidOrFail(string $ulid): mixed
    {
        return static::whereUlid($ulid)->firstOrFail();
    }

    /**
     * Get the name of the ULID column.
     */
    public function getUlidColumn(): string
    {
        return 'ulid';
    }

    /**
     * Get the route key for the model (use ULID for public URLs).
     */
    public function getRouteKeyName(): string
    {
        return $this->getUlidColumn();
    }

    /**
     * Resolve a route binding for the given value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field && $field !== $this->getUlidColumn()) {
            return parent::resolveRouteBinding($value, $field);
        }

        return static::where($this->getUlidColumn(), $value)->first();
    }

    /**
     * Get the ULID value.
     */
    public function getUlid(): string
    {
        return $this->{$this->getUlidColumn()};
    }

    /**
     * Scope a query to find a model by ULID.
     *
     * @param  mixed  $query
     */
    public function scopeWhereUlid($query, string $ulid): mixed
    {
        return $query->where($this->getUlidColumn(), $ulid);
    }

    /**
     * Boot the HasUlids trait for a model.
     */
    protected static function bootHasUlids(): void
    {
        static::creating(function ($model): void {
            if (empty($model->{$model->getUlidColumn()})) {
                $model->{$model->getUlidColumn()} = mb_strtolower((string) Str::ulid());
            }
        });
    }
}
