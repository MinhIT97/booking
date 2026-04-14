<?php

namespace Modules\Admin\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminUserRepository extends BaseRepository implements AdminUserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Get users with optional filtering.
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->newQuery()->with('role');

        // List all users except admins (or keep admins in if needed, usually management lists others)
        // Let's list everyone for a complete admin view, but user requested "user + host"
        // I'll filter by roles if provided
        
        if (!empty($filters['role'])) {
            $query->whereHas('role', function($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
