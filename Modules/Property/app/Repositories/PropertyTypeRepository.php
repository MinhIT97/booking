<?php

namespace Modules\Property\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Property\Models\PropertyType;

/**
 * Class PropertyTypeRepository.
 *
 * @package namespace Modules\Property\Repositories;
 */
class PropertyTypeRepository extends BaseRepository implements PropertyTypeRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PropertyType::class;
    }
}
