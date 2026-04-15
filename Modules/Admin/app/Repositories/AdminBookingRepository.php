<?php

namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;

class AdminBookingRepository extends BaseRepository implements AdminBookingRepositoryInterface
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function getPaginatedWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->newQuery()->with(['user', 'property.host']);

        if (!empty($filters['status'])) {
            $status = BookingStatus::fromInput($filters['status']);

            if ($status) {
                $query->where('status', $status->value);
            }
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($user) => $user
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('property', fn ($property) => $property
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%"));
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function findWithRelations(string $id): ?Model
    {
        return $this->newQuery()
            ->with(['user.role', 'property.host', 'property.primaryImage'])
            ->find($id);
    }

    public function countByStatus(BookingStatus $status): int
    {
        return $this->newQuery()->where('status', $status->value)->count();
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->newQuery()
            ->with(['user', 'property'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function revenueTotal(): float
    {
        return (float) $this->newQuery()
            ->whereIn('status', [BookingStatus::Confirmed->value, BookingStatus::Completed->value])
            ->sum('total_price');
    }
}
