<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository as L5BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Contracts\CacheableInterface;

/**
 * Class BaseRepository
 * 
 * Central Repository hub extending Prettus L5 Repository capabilities.
 * Implements CacheableInterface for automatic query caching.
 */
abstract class BaseRepository extends L5BaseRepository implements BaseRepositoryInterface, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract public function model();

    public function query()
    {
        return $this->model->newQuery();
    }

    /**
     * Boot up the repository, pushing criteria or settings.
     */
    public function boot()
    {
        // Global criteria can be pushed here
    }
}
