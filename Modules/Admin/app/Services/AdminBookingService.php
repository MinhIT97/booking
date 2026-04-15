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
        return $this->repository->getPaginatedWithFilters($filters, $perPage);
    }

    public function getBooking(string $id): ?Model
    {
        return $this->repository->findWithRelations($id);
    }

    public function updateStatus(string $id, string|int $status): bool
    {
        $bookingStatus = BookingStatus::fromInput($status);

        if (!$bookingStatus) {
            return false;
        }

        return $this->executeInTransaction(fn () => $this->repository->update($id, [
            'status' => $bookingStatus->value,
        ]));
    }

    public function deleteBooking(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function countByStatus(BookingStatus $status): int
    {
        return $this->repository->countByStatus($status);
    }

    public function recent(int $limit = 8): Collection
    {
        return $this->repository->recent($limit);
    }

    public function revenueTotal(): float
    {
        return $this->repository->revenueTotal();
    }
}
