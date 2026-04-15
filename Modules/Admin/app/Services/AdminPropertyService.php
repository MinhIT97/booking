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
        $this->repository = $repository;
    }

    /**
     * Get property list for moderation.
     */
    public function getPropertyList(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getPaginatedWithFilters($filters, $perPage);
    }

    public function getProperty(string $id): ?Model
    {
        return $this->repository->findWithRelations($id);
    }

    /**
     * Approve a property.
     */
    public function approveProperty(string $id): bool
    {
        return $this->repository->update($id, ['status' => PropertyStatus::Active->value]);
    }

    /**
     * Reject a property.
     */
    public function rejectProperty(string $id): bool
    {
        return $this->repository->update($id, ['status' => PropertyStatus::Rejected->value]);
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
        return $this->repository->countByStatus($status);
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->repository->recent($limit);
    }
}
