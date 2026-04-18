<?php

namespace Modules\Admin\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class AdminPropertySearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        $search = $filters['search'] ?? null;
        if (!$search) return $query;

        $term = "%{$search}%";
        $columns = ['title', 'city', 'address', 'state', 'country'];

        return $query->where(function ($q) use ($term, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', $term);
            }

            $q->orWhereHas('host', fn($h) => $h->where('name', 'like', $term));
        });
    }
}
