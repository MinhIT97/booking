<?php

namespace Modules\Admin\Services;

use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Repositories\AdminBookingRepositoryInterface;
use Modules\Booking\Enums\BookingStatus;

class AdminBookingService extends BaseService
{
    public function __construct(protected AdminBookingRepositoryInterface $repository) {}

    public function getBookingList(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository
            ->with(['user', 'property.host'])
            ->scopeQuery(function($query) use ($filters) {
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

                return $query->latest();
            })
            ->paginate($perPage);
    }

    public function getBooking(string $id): ?Model
    {
        return $this->repository
            ->with(['user.role', 'property.host', 'property.primaryImage'])
            ->find($id);
    }

    public function updateStatus(string $id, string|int $status): bool
    {
        $bookingStatus = BookingStatus::fromInput($status);

        if (!$bookingStatus) {
            return false;
        }

        return $this->executeInTransaction(function() use ($id, $bookingStatus) {
            $this->repository->update(['status' => $bookingStatus->value], $id);
            return true;
        });
    }

    public function deleteBooking(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function countByStatus(BookingStatus $status): int
    {
        return $this->repository->findWhere(['status' => $status->value])->count();
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->repository
            ->with(['user', 'property'])
            ->scopeQuery(function($q) use ($limit) {
                return $q->latest()->limit($limit);
            })
            ->get();
    }

    public function revenueTotal(): float
    {
        return (float) $this->repository->scopeQuery(function($q) {
            return $q->whereIn('status', [BookingStatus::Confirmed->value, BookingStatus::Completed->value]);
        })->sum('total_price');
    }
}
