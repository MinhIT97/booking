<?php

namespace Modules\Property\Services;

use App\Services\BaseService;
use Modules\Property\Repositories\PropertyRepositoryInterface;
use Modules\Property\Repositories\PropertyTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Property\Enums\PropertyStatus;

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
            ->scopeQuery(function($query) use ($filters) {
                if (!empty($filters['status'])) {
                    $query->where('status', $filters['status']);
                }

                if (!empty($filters['property_type_id'])) {
                    $query->where('property_type_id', $filters['property_type_id']);
                }

                if (!empty($filters['type_slug'])) {
                    $query->whereHas('propertyType', function($q) use ($filters) {
                        $q->where('slug', $filters['type_slug']);
                    });
                }

                if (!empty($filters['location'])) {
                    $location = '%' . $filters['location'] . '%';
                    $query->where(function($q) use ($location) {
                        $q->where('city', 'like', $location)
                          ->orWhere('state', 'like', $location)
                          ->orWhere('country', 'like', $location)
                          ->orWhere('title', 'like', $location)
                          ->orWhere('address', 'like', $location);
                    });
                }

                // Availability filter
                if (!empty($filters['dates']) && str_contains($filters['dates'], ' to ')) {
                    [$startStr, $endStr] = explode(' to ', $filters['dates']);
                    try {
                        $start = \Carbon\Carbon::parse($startStr)->format('Y-m-d');
                        $end = \Carbon\Carbon::parse($endStr)->format('Y-m-d');

                        $query->whereDoesntHave('bookings', function($q) use ($start, $end) {
                            $q->whereIn('status', [1, 2]) // 1: Pending, 2: Confirmed
                              ->where(function($q) use ($start, $end) {
                                  $q->where('check_in_date', '<', $end)
                                    ->where('check_out_date', '>', $start);
                              });
                        });
                    } catch (\Exception $e) {}
                }

                return $query;
            })
            ->paginate(15);
    }

    public function getPropertyTypes(): Collection
    {
        return $this->typeRepository->all();
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
            ->scopeQuery(function($query) use ($hostId, $filters) {
                $query->where('host_id', $hostId);

                if (!empty($filters['q'])) {
                    $q = '%' . $filters['q'] . '%';
                    $query->where(fn($b) => $b->where('title', 'like', $q)->orWhere('city', 'like', $q));
                }

                if (!empty($filters['property_type_id'])) {
                    $query->where('property_type_id', $filters['property_type_id']);
                }

                if (!empty($filters['status'])) {
                    $status = PropertyStatus::fromInput($filters['status']);
                    if ($status) {
                        $query->where('status', $status->value);
                    }
                }

                return $query->orderByDesc('created_at');
            })
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
}
