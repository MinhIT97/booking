<?php

namespace Modules\Admin\Services;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Services\BaseService;
use Modules\Admin\Repositories\AdminUserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

    public function getUser(string $userId): ?Model
    {
        return $this->repository->findWithRelations($userId);
    }

    public function updateUser(string $userId, array $data): bool
    {
        return $this->executeInTransaction(function () use ($userId, $data) {
            if (!empty($data['role'])) {
                $role = Role::where('name', $data['role'])->first();

                if (!$role) {
                    throw ValidationException::withMessages(['role' => 'Selected role is invalid.']);
                }

                $data['role_id'] = $role->id;
            }

            unset($data['role']);

            if (array_key_exists('status', $data)) {
                $status = UserStatus::fromInput($data['status']);
                unset($data['status']);

                if ($status) {
                    $data['status'] = $status->value;
                }
            }

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            return $this->repository->update($userId, $data);
        });
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

        $newStatus = $user->status === UserStatus::Active
            ? UserStatus::Inactive
            : UserStatus::Active;

        return $this->repository->update($userId, ['status' => $newStatus->value]);
    }

    /**
     * Approve a user (usually a host).
     */
    public function approveUser(string $userId): bool
    {
        return $this->repository->update($userId, ['status' => UserStatus::Active->value]);
    }

    /**
     * Block a user.
     */
    public function blockUser(string $userId): bool
    {
        return $this->repository->update($userId, ['status' => UserStatus::Blocked->value]);
    }

    public function deleteUser(string $userId, ?string $currentUserId = null): bool
    {
        if ($currentUserId && $userId === $currentUserId) {
            return false;
        }

        return $this->repository->delete($userId);
    }

    public function countByStatus(UserStatus $status): int
    {
        return $this->repository->countByStatus($status);
    }

    public function countByRole(string $role): int
    {
        return $this->repository->countByRole($role);
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->repository->recent($limit);
    }
}
