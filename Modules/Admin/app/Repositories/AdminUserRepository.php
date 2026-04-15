<?php

namespace Modules\Admin\Repositories;

use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Role;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
            $status = UserStatus::fromInput($filters['status']);

            if ($status) {
                $query->where('status', $status->value);
            }
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

    public function findWithRelations(string $id): ?Model
    {
        return $this->newQuery()
            ->with(['role', 'properties.primaryImage', 'bookings.property'])
            ->find($id);
    }

    public function countByStatus(UserStatus $status): int
    {
        return $this->newQuery()->where('status', $status->value)->count();
    }

    public function countByRole(string $role): int
    {
        return $this->newQuery()
            ->whereHas('role', fn ($query) => $query->where('name', $role))
            ->count();
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->newQuery()
            ->with('role')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
