<?php

namespace Modules\Property\Services;

use App\Services\BaseService;
use Modules\Property\Repositories\PropertyRepositoryInterface;
use Modules\Property\Repositories\PropertyTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Property\Filters\PropertyFilterPipeline;
use Modules\Property\Enums\PropertyStatus;
use Illuminate\Support\Facades\Cache;

class PropertyService extends BaseService
{
    public function __construct(
        protected PropertyRepositoryInterface $repository,
        protected PropertyTypeRepositoryInterface $typeRepository
    ) {}

    public function getAllProperties(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;
        
        return Cache::remember(
            'properties:list:' . md5(json_encode($filters)),
            600,
            fn() => $this->buildFilteredQuery($filters)
                ->select($this->getListColumns())
                ->paginate($perPage)
        );
    }

    public function getPropertyTypes(): Collection
    {
        return $this->typeRepository->all();
    }

    public function getAvailableLocations(): array
    {
        return $this->repository->query()
            ->where('status', PropertyStatus::Active)
            ->select('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->values()
            ->toArray();
    }

    public function getPropertyById(string $id): ?Model
    {
        return $this->repository->query()
            ->with($this->getDetailRelations())
            ->find($id);
    }

    public function getPropertyBySlug(string $slug): ?Model
    {
        return $this->repository->scopeQuery(fn($q) => $q
            ->with($this->getDetailRelations())
            ->where('slug', $slug)
        )->first();
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

    public function getByHost(string $hostId, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 9;

        return $this->buildFilteredQuery($filters, $hostId)
            ->withCount('bookings')
            ->paginate($perPage);
    }

    public function countByHost(string $hostId): int
    {
        return $this->repository->findWhere(['host_id' => $hostId])->count();
    }

    public function countByHostThisMonth(string $hostId): int
    {
        return $this->repository->scopeQuery(fn($q) => $q
            ->where('host_id', $hostId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
        )->count();
    }

    public function topByHost(string $hostId, int $limit = 5): Collection
    {
        return $this->repository->query()
            ->where('host_id', $hostId)
            ->with(['primaryImage'])
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit($limit)
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

    /* ── Private Helpers ─────────────────────────────────────── */

    private function buildFilteredQuery(array $filters = [], ?string $hostId = null): Builder
    {
        $query = $this->repository->query()
            ->with($this->getListRelations());

        if ($hostId) {
            $query->where('host_id', $hostId);
        }

        return (new PropertyFilterPipeline($filters))->apply($query);
    }

    private function getListRelations(): array
    {
        return ['primaryImage', 'propertyType', 'host'];
    }

    private function getDetailRelations(): array
    {
        return ['primaryImage', 'propertyType', 'host', 'images', 'user'];
    }

    private function getListColumns(): array
    {
        return [
            'id', 'host_id', 'property_type_id', 'title', 'slug', 
            'price_per_night', 'max_guests', 'bedrooms', 'bathrooms', 
            'city', 'average_rating', 'status', 'created_at'
        ];
    }
}
