<?php

namespace Modules\Admin\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class AdminUserRepository extends BaseRepository implements AdminUserRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }
}
