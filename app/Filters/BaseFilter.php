<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseFilter
{
    /**
     * The Eloquent builder instance.
     */
    protected Builder $builder;

    /**
     * Create a new filter instance.
     */
    public function __construct(protected Request $request) {}

    /**
     * Apply all dynamically mapped filters to the builder.
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            $method = 'filter' . Str::studly($name);

            if (method_exists($this, $method)) {
                if ($this->isValidValue($value)) {
                    $this->{$method}($value);
                }
            } else {
                $this->applyDynamicMapping($name, $value);
            }
        }

        return $this->builder;
    }

    /**
     * Automatically maps query boundaries dynamically when an explicit method does not exist.
     */
    protected function applyDynamicMapping(string $name, mixed $value): void
    {
        if (!$this->isValidValue($value)) {
            return;
        }

        if (Str::endsWith($name, '_min')) {
            $column = Str::replaceLast('_min', '', $name);
            $this->builder->where($column, '>=', $value);
        } elseif (Str::endsWith($name, '_max')) {
            $column = Str::replaceLast('_max', '', $name);
            $this->builder->where($column, '<=', $value);
        } elseif (is_array($value)) {
            $this->builder->whereIn($name, $value);
        } else {
            $this->builder->where($name, $value);
        }
    }

    /**
     * Extract the explicit array of filter parameters from the request payload.
     */
    public function filters(): array
    {
        return $this->request->all();
    }

    /**
     * Validate parameter existence.
     */
    protected function isValidValue(mixed $value): bool
    {
        return $value !== null && $value !== '';
    }
}
