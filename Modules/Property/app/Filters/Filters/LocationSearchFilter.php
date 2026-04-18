<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class LocationSearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['location'] ?? null,
            function($q, $locations) {
                $locations = (array) $locations;
                $locations = array_filter($locations);

                if (empty($locations)) return $q;

                return $q->where(function($sub) use ($locations) {
                    foreach ($locations as $loc) {
                        $search = "%{$loc}%";
                        $sub->orWhere(function($inner) use ($search) {
                            $inner->where('city', 'like', $search)
                                  ->orWhere('state', 'like', $search)
                                  ->orWhere('country', 'like', $search)
                                  ->orWhere('title', 'like', $search)
                                  ->orWhere('address', 'like', $search);
                        });
                    }
                });
            }
        );
    }
}
