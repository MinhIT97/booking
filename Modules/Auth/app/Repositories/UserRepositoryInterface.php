<?php

namespace Modules\Auth\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find a user by their email address.
     *
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByEmail(string $email);
}
