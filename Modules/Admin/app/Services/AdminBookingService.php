<?php

namespace Modules\Admin\Services;

use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Repositories\AdminBookingRepositoryInterface;
use Modules\Booking\Enums\BookingStatus;

use Illuminate\Database\Eloquent\Builder;
use Modules\Booking\Filters\BookingFilterPipeline;

class AdminBookingService extends BaseService
{
    public function __construct(protected AdminBookingRepositoryInterface $repository) {}

    public function getBookingList(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? $perPage;

        return $this->buildFilteredQuery($filters)
            ->paginate($perPage);
    }

    public function getBooking(string $id): ?Model
    {
        return $this->repository->query()
            ->with($this->getDetailRelations())
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
        return $this->repository->query()
            ->with($this->getListRelations())
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function revenueTotal(): float
    {
        return (float) $this->repository->query()
            ->whereIn('status', [BookingStatus::Confirmed->value, BookingStatus::Completed->value])
            ->sum('total_price');
    }

    /* ── Private Helpers ─────────────────────────────────────── */

    private function buildFilteredQuery(array $filters = []): Builder
    {
        $query = $this->repository->query()
            ->with($this->getListRelations());

        return (new BookingFilterPipeline($filters))->apply($query);
    }

    private function getListRelations(): array
    {
        return ['user', 'property.host'];
    }

    private function getDetailRelations(): array
    {
        return ['user.role', 'property.host', 'property.primaryImage'];
    }
}
