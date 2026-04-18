<?php

namespace Modules\Property\Filters\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Exception;

class AvailabilityFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        $dates = $filters['dates'] ?? null;

        if (!$dates || !str_contains($dates, ' to ')) {
            return $query;
        }

        try {
            [$startStr, $endStr] = explode(' to ', $dates);
            $start = Carbon::parse($startStr)->format('Y-m-d');
            $end = Carbon::parse($endStr)->format('Y-m-d');

            return $query->whereDoesntHave('bookings', function($q) use ($start, $end) {
                $q->whereIn('status', [
                    \Modules\Booking\Enums\BookingStatus::Pending->value,
                    \Modules\Booking\Enums\BookingStatus::Confirmed->value,
                ])
                ->where('check_in_date', '<', $end)
                ->where('check_out_date', '>', $start);
            });
        } catch (Exception) {
            return $query;
        }
    }
}
