<?php

namespace App\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterContract
{
    /**
     * Apply the filter to the query builder.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function apply(Builder $query, array $filters): Builder;
}
