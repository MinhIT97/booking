<?php

namespace Modules\Property\Services;

use App\Services\BaseService;
use Modules\Property\Repositories\PropertyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PropertyService extends BaseService
{
    /**
     * Inject our explicit Repository interface, keeping the Service completely abstracted from DB calls.
     */
    public function __construct(protected PropertyRepositoryInterface $repository) {}

    public function getAllProperties(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->with(['user', 'images'])->paginate(15);
    }

    public function getPropertyById(string $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function createProperty(array $data, ?string $hostId = null): Model
    {
        if ($hostId) {
            $data['host_id'] = $hostId;
        }

        return $this->executeInTransaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    public function updateProperty(Model|string $property, array $data): bool
    {
        $id = $property instanceof Model ? $property->getKey() : $property;

        return $this->executeInTransaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });
    }

    public function deleteProperty(Model|string $property): bool
    {
        $id = $property instanceof Model ? $property->getKey() : $property;

        return $this->repository->delete($id);
    }

    /* ── Host-scoped helpers ─────────────────────────────────── */

    /**
     * Get paginated properties belonging to a specific host, with optional filters.
     */
    public function getByHost(string $hostId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->repository->newHostQuery($hostId);

        if (!empty($filters['q'])) {
            $q = '%' . $filters['q'] . '%';
            $query->where(fn($b) => $b->where('title', 'like', $q)->orWhere('city', 'like', $q));
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with(['images', 'primaryImage'])->withCount('bookings')->orderByDesc('created_at')->paginate(9);
    }

    public function countByHost(string $hostId): int
    {
        return $this->repository->newHostQuery($hostId)->count();
    }

    public function countByHostThisMonth(string $hostId): int
    {
        return $this->repository->newHostQuery($hostId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function topByHost(string $hostId, int $limit = 5): Collection
    {
        return $this->repository->newHostQuery($hostId)
            ->with(['primaryImage'])
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit($limit)
            ->get();
    }

    public function averageRatingByHost(string $hostId): float
    {
        return (float) ($this->repository->newHostQuery($hostId)->avg('average_rating') ?? 0.0);
    }

    public function totalReviewsByHost(string $hostId): int
    {
        return (int) $this->repository->newHostQuery($hostId)->sum('reviews_count');
    }
}
