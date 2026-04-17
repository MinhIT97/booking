<?php

namespace Modules\Admin\Repositories;

use Modules\Property\Models\Property;
use App\Repositories\BaseRepository;

class AdminPropertyRepository extends BaseRepository implements AdminPropertyRepositoryInterface
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
}
