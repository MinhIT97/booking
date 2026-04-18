<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class TypeFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            ($filters['type_slug'] ?? null) && $filters['type_slug'] !== 'all',
            fn($q, $slug) => $q->whereHas('propertyType', fn($sub) => $sub->where('slug', $slug))
        );
    }
}
