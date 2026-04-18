<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['status'] ?? null,
            fn($q, $status) => $q->where('status', $status)
        );
    }
}
