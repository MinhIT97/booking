<?php

namespace Modules\Property\Services;

use App\Services\BaseService;
use Modules\Property\Repositories\PropertyRepositoryInterface;
use Modules\Property\Repositories\PropertyTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Property\Criteria\PropertySearchCriteria;
use Modules\Property\Criteria\HostPropertiesCriteria;

class PropertyService extends BaseService
{
    /**
     * Inject Repository interfaces.
     */
    public function __construct(
        protected PropertyRepositoryInterface $repository,
        protected PropertyTypeRepositoryInterface $typeRepository
    ) {}

    public function getAllProperties(array $filters = []): LengthAwarePaginator
    {
        return $this->repository
            ->with(['user', 'images', 'primaryImage', 'host', 'propertyType'])
            ->pushCriteria(new PropertySearchCriteria($filters))
            ->paginate(15);
    }

    public function getPropertyTypes(): Collection
    {
        return $this->typeRepository->all();
    }

    public function getAvailableLocations(): array
    {
        return $this->repository
            ->findWhere(['status' => 2])
            ->pluck('city')
            ->unique()
            ->filter()
            ->values()
            ->toArray();
    }

    public function getPropertyById(string $id): ?Model
    {
        return $this->repository->with(['host', 'images', 'primaryImage', 'propertyType'])->find($id);
    }

    public function getPropertyBySlug(string $slug): ?Model
    {
        return $this->repository->with(['host', 'images', 'primaryImage', 'propertyType'])
            ->findWhere(['slug' => $slug])
            ->first();
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
            $this->repository->update($data, $id);
            return true;
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
        return $this->repository
            ->with(['images', 'primaryImage', 'propertyType'])
            ->withCount('bookings')
            ->pushCriteria(new HostPropertiesCriteria($hostId, $filters))
            ->paginate(9);
    }

    public function countByHost(string $hostId): int
    {
        return $this->repository->findWhere(['host_id' => $hostId])->count();
    }

    public function countByHostThisMonth(string $hostId): int
    {
        return $this->repository->scopeQuery(function($q) use ($hostId) {
            return $q->where('host_id', $hostId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        })->count();
    }

    public function topByHost(string $hostId, int $limit = 5): Collection
    {
        return $this->repository
            ->with(['primaryImage'])
            ->withCount('bookings')
            ->scopeQuery(function($q) use ($hostId, $limit) {
                return $q->where('host_id', $hostId)
                    ->orderByDesc('bookings_count')
                    ->limit($limit);
            })
            ->get();
    }

    public function averageRatingByHost(string $hostId): float
    {
        return (float) ($this->repository->findWhere(['host_id' => $hostId])->avg('average_rating') ?? 0.0);
    }

    public function totalReviewsByHost(string $hostId): int
    {
        return (int) $this->repository->findWhere(['host_id' => $hostId])->sum('reviews_count');
    }
}
