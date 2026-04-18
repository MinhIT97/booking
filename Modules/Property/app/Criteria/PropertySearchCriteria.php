<?php

namespace Modules\Property\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PropertySearchCriteria implements CriteriaInterface
{
    public function __construct(protected array $filters) {}

    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($this->filters['status'])) {
            $model = $model->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['property_type_id'])) {
            $model = $model->where('property_type_id', $this->filters['property_type_id']);
        }

        if (!empty($this->filters['type_slug'])) {
            $model = $model->whereHas('propertyType', function($q) {
                $q->where('slug', $this->filters['type_slug']);
            });
        }

        if (!empty($this->filters['location'])) {
            $location = '%' . $this->filters['location'] . '%';
            $model = $model->where(function($q) use ($location) {
                $q->where('city', 'like', $location)
                  ->orWhere('state', 'like', $location)
                  ->orWhere('country', 'like', $location)
                  ->orWhere('title', 'like', $location)
                  ->orWhere('address', 'like', $location);
            });
        }

        // Availability filter
        if (!empty($this->filters['dates']) && str_contains($this->filters['dates'], ' to ')) {
            [$startStr, $endStr] = explode(' to ', $this->filters['dates']);
            try {
                $start = \Carbon\Carbon::parse($startStr)->format('Y-m-d');
                $end = \Carbon\Carbon::parse($endStr)->format('Y-m-d');

                $model = $model->whereDoesntHave('bookings', function($q) use ($start, $end) {
                    $q->whereIn('status', [1, 2]) // 1: Pending, 2: Confirmed
                      ->where(function($q) use ($start, $end) {
                          $q->where('check_in_date', '<', $end)
                            ->where('check_out_date', '>', $start);
                      });
                });
            } catch (\Exception $e) {}
        }

        return $model;
    }
}
