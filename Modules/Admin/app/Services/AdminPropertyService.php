<?php

namespace Modules\Admin\Services;

use App\Services\BaseService;
use Modules\Admin\Repositories\AdminPropertyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Property\Enums\PropertyStatus;

class AdminPropertyService extends BaseService
{
    public function __construct(protected AdminPropertyRepositoryInterface $repository)
    {
    }

    /**
     * Get property list for moderation.
     */
    public function getPropertyList(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository
            ->with(['host', 'primaryImage'])
            ->scopeQuery(function($query) use ($filters) {
                if (!empty($filters['status'])) {
                    $status = PropertyStatus::fromInput($filters['status']);
                    if ($status) {
                        $query->where('status', $status->value);
                    }
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

                return $query->latest();
            })
            ->paginate($perPage);
    }

    public function getProperty(string $id): ?Model
    {
        return $this->repository
            ->with(['host.role', 'images', 'primaryImage', 'bookings.user'])
            ->withCount('bookings')
            ->find($id);
    }

    /**
     * Approve a property.
     */
    public function approveProperty(string $id): bool
    {
        $this->repository->update(['status' => PropertyStatus::Active->value], $id);
        return true;
    }

    /**
     * Reject a property.
     */
    public function rejectProperty(string $id): bool
    {
        $this->repository->update(['status' => PropertyStatus::Rejected->value], $id);
        return true;
    }

    /**
     * Delete a property.
     */
    public function deleteProperty(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function countByStatus(PropertyStatus $status): int
    {
        return $this->repository->findWhere(['status' => $status->value])->count();
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->repository
            ->with(['host', 'primaryImage'])
            ->scopeQuery(function($q) use ($limit) {
                return $q->latest()->limit($limit);
            })
            ->get();
    }
}
