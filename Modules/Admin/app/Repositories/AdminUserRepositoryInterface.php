<?php

namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Enums\UserStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AdminUserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get users with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function findWithRelations(string $id): ?Model;

    public function countByStatus(UserStatus $status): int;

    public function countByRole(string $role): int;

    public function recent(int $limit = 8): Collection;
}
