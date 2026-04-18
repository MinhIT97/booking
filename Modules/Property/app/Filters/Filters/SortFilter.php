<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class SortFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        $sort = $filters['sort'] ?? 'recommended';

        return match ($sort) {
            'price-asc' => $query->orderBy('price_per_night'),
            'price-desc' => $query->orderByDesc('price_per_night'),
            'rating' => $query->orderByDesc('average_rating'),
            default => $query->orderByDesc('created_at'),
        };
    }
}
