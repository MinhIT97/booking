<?php

namespace Modules\Booking\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class BookingSearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        $search = $filters['search'] ?? null;
        if (!$search) return $query;

        $term = "%{$search}%";

        return $query->where(function ($q) use ($term) {
            $q->whereHas('user', fn($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term))
              ->orWhereHas('property', fn($p) => $p->where('title', 'like', $term)->orWhere('city', 'like', $term));
        });
    }
}
