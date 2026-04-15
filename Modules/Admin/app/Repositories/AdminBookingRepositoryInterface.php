<?php

namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Enums\BookingStatus;

interface AdminBookingRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function findWithRelations(string $id): ?Model;

    public function countByStatus(BookingStatus $status): int;

    public function recent(int $limit = 8): Collection;

    public function revenueTotal(): float;
}
