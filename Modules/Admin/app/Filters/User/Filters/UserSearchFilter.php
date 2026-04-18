<?php

namespace Modules\Admin\Filters\User\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class UserSearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        $search = $filters['search'] ?? null;
        if (!$search) return $query;

        $term = "%{$search}%";

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', $term)
              ->orWhere('email', 'like', $term);
        });
    }
}
