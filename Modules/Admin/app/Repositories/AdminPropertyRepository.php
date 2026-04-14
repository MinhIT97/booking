<?php

namespace Modules\Admin\Repositories;

use Modules\Property\Models\Property;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminPropertyRepository extends BaseRepository implements AdminPropertyRepositoryInterface
{
    public function __construct(Property $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated properties with filtering.
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->newQuery()->with(['host', 'primaryImage']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('host', function($h) use ($search) {
                      $h->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
