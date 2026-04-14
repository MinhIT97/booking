<?php

namespace Modules\Property\Repositories;

use App\Repositories\BaseRepository;
use Modules\Property\Models\Property;

class PropertyRepository extends BaseRepository implements PropertyRepositoryInterface
{
    /**
     * Automatically inject the native Model here.
     */
    public function __construct(Property $model)
    {
        parent::__construct($model);
    }

    public function newHostQuery(string $hostId)
    {
        return $this->newQuery()->where('host_id', $hostId);
    }
}
