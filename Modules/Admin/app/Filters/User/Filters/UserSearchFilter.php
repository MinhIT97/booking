<?php

namespace Modules\Admin\Filters\User\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class UserSearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['search'] ?? null,
            fn($q, $search) => $q->where(fn($sub) => $sub
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            )
        );
    }
}
