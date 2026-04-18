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

        if (!empty($this->filters['type_slug']) && $this->filters['type_slug'] !== 'all') {
            $model = $model->whereHas('propertyType', function($q) {
                $q->where('slug', $this->filters['type_slug']);
            });
        }

        if (!empty($this->filters['location'])) {
            $locations = (array) $this->filters['location'];
            $model = $model->where(function($q) use ($locations) {
                foreach ($locations as $loc) {
                    if (empty($loc)) continue;
                    $search = '%' . $loc . '%';
                    $q->orWhere('city', 'like', $search)
                      ->orWhere('state', 'like', $search)
                      ->orWhere('country', 'like', $search)
                      ->orWhere('title', 'like', $search)
                      ->orWhere('address', 'like', $search);
                }
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

        // Price filters
        if (isset($this->filters['min_price']) || isset($this->filters['max_price'])) {
            $p1 = isset($this->filters['min_price']) ? (int) $this->filters['min_price'] : 0;
            $p2 = isset($this->filters['max_price']) ? (int) $this->filters['max_price'] : 1000;
            
            $minPrice = min($p1, $p2);
            $maxPrice = max($p1, $p2);
            
            $model = $model->where('price_per_night', '>=', $minPrice)
                           ->where('price_per_night', '<=', $maxPrice);
        }

        // Rating filter
        if (isset($this->filters['min_rating'])) {
            $model = $model->where('average_rating', '>=', (float) $this->filters['min_rating']);
        }

        // Sorting
        $sort = $this->filters['sort'] ?? 'recommended';
        if ($sort === 'price-asc') {
            $model = $model->orderBy('price_per_night', 'asc');
        } elseif ($sort === 'price-desc') {
            $model = $model->orderBy('price_per_night', 'desc');
        } elseif ($sort === 'rating') {
            $model = $model->orderBy('average_rating', 'desc');
        } else {
            $model = $model->orderBy('created_at', 'desc'); // recommended fallback
        }

        return $model;
    }
}
