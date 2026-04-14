<?php

namespace Modules\Booking\Repositories;

use App\Repositories\BaseRepository;
use Modules\Booking\Models\Booking;
use Illuminate\Support\Facades\Cache;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function checkOverlap(string $propertyId, string $checkIn, string $checkOut): bool
    {
        return $this->model->where('property_id', $propertyId)
            ->where('status', 'confirmed') // CRITICAL: Only confirmed bookings block dates!
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                      ->orWhere(function ($q) use ($checkIn, $checkOut) {
                          $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                      });
            })->exists();
    }

    public function getBookingsByProperty(string $propertyId)
    {
        return $this->findBy(['property_id' => $propertyId]);
    }

    public function getUnavailableDates(string $propertyId, string $startDate, string $endDate)
    {
        $key = $this->getCacheKey('getUnavailableDates', [$propertyId, $startDate, $endDate]);
        
        return Cache::tags($this->getCacheTags())->remember($key, $this->cacheTtl, function () use ($propertyId, $startDate, $endDate) {
            return $this->newQuery()
                ->where('property_id', $propertyId)
                ->where('status', 'confirmed') // Only confirmed bookings impact actual availability calendar!
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('check_in_date', [$startDate, $endDate])
                          ->orWhereBetween('check_out_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('check_in_date', '<=', $startDate)
                                ->where('check_out_date', '>=', $endDate);
                          });
                })
                ->select(['check_in_date', 'check_out_date']) // Drop all nested nested relationships preventing N+1 overload and blob bloat
                ->get()
                ->map(function ($booking) {
                    return [
                        'start' => $booking->check_in_date->toDateString(),
                        'end' => $booking->check_out_date->toDateString()
                    ];
                });
        });
    }

    public function newHostQuery(string $hostId)
    {
        return $this->newQuery()->whereHas('property', function ($query) use ($hostId) {
            $query->where('host_id', $hostId);
        });
    }
}
