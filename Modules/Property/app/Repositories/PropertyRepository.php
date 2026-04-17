<?php

namespace Modules\Property\Repositories;

use App\Repositories\BaseRepository;
use Modules\Property\Models\Property;

class PropertyRepository extends BaseRepository implements PropertyRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Property::class;
    }

    /**
     * Return a builder scoped to a specific host_id.
     * Note: In L5 Repository, we can use the model directly or better, push a criteria.
     * For backward compatibility with existing service calls, we return the model query.
     */
    public function newHostQuery(string $hostId)
    {
        return $this->model->where('host_id', $hostId);
    }
}
