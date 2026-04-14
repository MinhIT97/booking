<?php

namespace Modules\Admin\Services;

use App\Services\BaseService;
use Modules\Admin\Repositories\AdminPropertyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    /**
     * Approve a property.
     */
    public function approveProperty(string $id): bool
    {
        return $this->repository->update($id, ['status' => 'approved']);
    }

    /**
     * Reject a property.
     */
    public function rejectProperty(string $id): bool
    {
        return $this->repository->update($id, ['status' => 'rejected']);
    }

    /**
     * Delete a property.
     */
    public function deleteProperty(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
