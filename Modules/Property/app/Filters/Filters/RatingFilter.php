<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class RatingFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            isset($filters['min_rating']),
            fn($q) => $q->where('average_rating', '>=', (float) $filters['min_rating'])
        );
    }
}
