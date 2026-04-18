<?php

namespace Modules\Booking\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use Modules\Booking\Enums\BookingStatus;

class StatusFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['status'] ?? null,
            function($q, $input) {
                $status = BookingStatus::fromInput($input);
                return $status ? $q->where('status', $status->value) : $q;
            }
        );
    }
}
