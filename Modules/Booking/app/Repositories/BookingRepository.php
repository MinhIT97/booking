<?php

namespace Modules\Booking\Repositories;

use App\Repositories\BaseRepository;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Booking::class;
    }

    public function checkOverlap(string $propertyId, string $checkIn, string $checkOut): bool
    {
        return $this->model->where('property_id', $propertyId)
            ->where('status', BookingStatus::Confirmed->value)
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
        return $this->findWhere(['property_id' => $propertyId]);
    }

    public function getUnavailableDates(string $propertyId, string $startDate, string $endDate)
    {
        return $this->model
            ->where('property_id', $propertyId)
            ->where('status', BookingStatus::Confirmed->value)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in_date', [$startDate, $endDate])
                      ->orWhereBetween('check_out_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('check_in_date', '<=', $startDate)
                            ->where('check_out_date', '>=', $endDate);
                      });
            })
            ->select(['check_in_date', 'check_out_date'])
            ->get()
            ->map(function ($booking) {
                return [
                    'start' => $booking->check_in_date->toDateString(),
                    'end' => $booking->check_out_date->toDateString()
                ];
            });
    }

    public function newHostQuery(string $hostId)
    {
        return $this->model->whereHas('property', function ($query) use ($hostId) {
            $query->where('host_id', $hostId);
        });
    }
}
