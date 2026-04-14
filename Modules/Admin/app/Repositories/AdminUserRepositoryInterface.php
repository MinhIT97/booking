<?php

namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}
