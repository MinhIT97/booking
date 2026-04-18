<?php

namespace Modules\Booking\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class BookingSearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['search'] ?? null,
            function($q, $search) {
                return $q->where(function ($sub) use ($search) {
                    $sub->whereHas('user', fn ($u) => $u
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('property', fn ($p) => $p
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%"));
                });
            }
        );
    }
}
