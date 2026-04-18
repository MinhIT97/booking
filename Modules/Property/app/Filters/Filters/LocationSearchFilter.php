<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class LocationSearchFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        $locations = array_filter((array) ($filters['location'] ?? []));

        if (empty($locations)) {
            return $query;
        }

        $columns = ['city', 'state', 'country', 'title', 'address'];

        return $query->where(function ($q) use ($locations, $columns) {
            foreach ($locations as $loc) {
                $term = "%{$loc}%";
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', $term);
                }
            }
        });
    }
}
