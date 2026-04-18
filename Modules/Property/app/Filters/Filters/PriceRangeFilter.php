<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class PriceRangeFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        $min = $filters['min_price'] ?? null;
        $max = $filters['max_price'] ?? null;

        if ($min === null && $max === null) {
            return $query;
        }

        $p1 = (int) ($min ?? 0);
        $p2 = (int) ($max ?? 1000000);

        return $query->whereBetween('price_per_night', [min($p1, $p2), max($p1, $p2)]);
    }
}
