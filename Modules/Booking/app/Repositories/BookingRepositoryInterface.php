<?php

namespace Modules\Booking\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface BookingRepositoryInterface extends BaseRepositoryInterface
{
    public function checkOverlap(string $propertyId, string $checkIn, string $checkOut): bool;
    
    public function getBookingsByProperty(string $propertyId);

    /**
     * Retrieve confirmed booked date ranges for a property within a window.
     * Guaranteed to use Redis caching under the hood.
     */
    public function getUnavailableDates(string $propertyId, string $startDate, string $endDate);
}
