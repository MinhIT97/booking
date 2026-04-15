<?php

namespace App\Repositories;

use Modules\Booking\Models\Booking;
use Illuminate\Support\Collection;

class BookingRepository
{
    public function getUserBookings(string $userId): Collection
    {
        return Booking::with('property')->where('user_id', $userId)->get();
    }

    public function findById(string $id): ?Booking
    {
        return Booking::find($id);
    }

    public function getPropertyBookings(string $propertyId): Collection
    {
        return Booking::where('property_id', $propertyId)->get();
    }

    public function isPropertyAvailable(string $propertyId, string $checkIn, string $checkOut): bool
    {
        return !Booking::where('property_id', $propertyId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                      ->orWhere(function ($q) use ($checkIn, $checkOut) {
                          $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                      });
            })->exists();
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function updateStatus(Booking $booking, string $status): bool
    {
        return $booking->update(['status' => $status]);
    }
}
