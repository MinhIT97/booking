<?php

namespace Modules\Auth\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Inject the foundational application User model into the base repository.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email)
    {
        return $this->newQuery()->where('email', $email)->first();
    }
}
