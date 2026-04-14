<?php

namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminPropertyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated properties for administration.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;
}
