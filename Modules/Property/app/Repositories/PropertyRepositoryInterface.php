<?php

namespace Modules\Property\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface PropertyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Return a base query scoped to a specific host_id.
     * Used by PropertyService for host dashboard aggregates.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newHostQuery(string $hostId);
}
