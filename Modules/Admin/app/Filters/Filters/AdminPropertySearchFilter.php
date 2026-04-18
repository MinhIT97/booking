<?php

namespace Modules\Admin\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class AdminPropertySearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['search'] ?? null,
            function($q, $search) {
                return $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhereHas('host', function($h) use ($search) {
                            $h->where('name', 'like', "%{$search}%");
                        });
                });
            }
        );
    }
}
