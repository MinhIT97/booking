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

use Illuminate\Database\Eloquent\Builder;
use Modules\Admin\Filters\User\UserFilterPipeline;

class AdminUserService extends BaseService
{
    public function __construct(protected AdminUserRepositoryInterface $repository) {}

    /**
     * Get paginated user list for admin.
     */
    public function getUserList(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? $perPage;

        return $this->buildFilteredQuery($filters)
            ->paginate($perPage);
    }

    public function getUser(string $userId): ?Model
    {
        return $this->repository->query()
            ->with($this->getDetailRelations())
            ->find($userId);
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

            $this->repository->update($data, $userId);
            return true;
        });
    }

    /**
     * Toggle user status.
     */
    public function toggleUserStatus(string $userId): bool
    {
        return $this->executeInTransaction(function() use ($userId) {
            $user = $this->repository->find($userId);
            if (!$user) {
                return false;
            }

            $newStatus = $user->status === UserStatus::Active
                ? UserStatus::Inactive
                : UserStatus::Active;

            $this->repository->update(['status' => $newStatus->value], $userId);
            return true;
        });
    }

    /**
     * Approve a user (usually a host).
     */
    public function approveUser(string $userId): bool
    {
        return $this->executeInTransaction(function() use ($userId) {
            $this->repository->update(['status' => UserStatus::Active->value], $userId);
            return true;
        });
    }

    /**
     * Block a user.
     */
    public function blockUser(string $userId): bool
    {
        return $this->executeInTransaction(function() use ($userId) {
            $this->repository->update(['status' => UserStatus::Blocked->value], $userId);
            return true;
        });
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
        return $this->repository->findWhere(['status' => $status->value])->count();
    }

    public function countByRole(string $role): int
    {
        return $this->repository->query()
            ->whereHas('role', fn ($query) => $query->where('name', $role))
            ->count();
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->repository->query()
            ->with($this->getListRelations())
            ->latest()
            ->limit($limit)
            ->get();
    }

    /* ── Private Helpers ─────────────────────────────────────── */

    private function buildFilteredQuery(array $filters = []): Builder
    {
        $query = $this->repository->query()
            ->with($this->getListRelations());

        return (new UserFilterPipeline($filters))->apply($query);
    }

    private function getListRelations(): array
    {
        return ['role'];
    }

    private function getDetailRelations(): array
    {
        return ['role', 'properties.primaryImage', 'bookings.property'];
    }
}
