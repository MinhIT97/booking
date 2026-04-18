<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BaseRepositoryInterface
 * 
 * Generic extension of Prettus RepositoryInterface to provide project-specific 
 * shared methods if needed in the future.
 */
interface BaseRepositoryInterface extends RepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query();
}
