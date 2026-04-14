<?php

namespace Modules\Admin\Services;

use App\Services\BaseService;
use Modules\Admin\Repositories\AdminUserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminUserService extends BaseService
{

    public function __construct(protected AdminUserRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    /**
     * Get paginated user list for admin.
     */
    public function getUserList(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getPaginatedWithFilters($filters, $perPage);
    }

    /**
     * Toggle user status.
     */
    public function toggleUserStatus(string $userId): bool
    {
        $user = $this->repository->find($userId);
        if (!$user) {
            return false;
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        return $this->repository->update($userId, ['status' => $newStatus]);
    }

    /**
     * Approve a user (usually a host).
     */
    public function approveUser(string $userId): bool
    {
        return $this->repository->update($userId, ['status' => 'active']);
    }

    /**
     * Block a user.
     */
    public function blockUser(string $userId): bool
    {
        return $this->repository->update($userId, ['status' => 'blocked']);
    }
}
