<?php

namespace Modules\Admin\Filters\User\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class RoleFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['role'] ?? null,
            fn($q, $role) => $q->whereHas('role', fn($sub) => $sub->where('name', $role))
        );
    }
}
