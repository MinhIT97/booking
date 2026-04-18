<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class BasePipeline
{
    /**
     * The filters from the request.
     */
    protected array $filters;

    /**
     * The array of filter classes to apply.
     */
    protected array $pipes = [];

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Apply the pipeline to the query builder.
     */
    public function apply(Builder $query): Builder
    {
        foreach ($this->pipes as $pipe) {
            $query = app($pipe)->apply($query, $this->filters);
        }

        return $query;
    }
}
