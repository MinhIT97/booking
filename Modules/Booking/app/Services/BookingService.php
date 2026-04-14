<?php

namespace Modules\Booking\Services;

use App\Services\BaseService;
use Modules\Booking\Repositories\BookingRepositoryInterface;
use Modules\Property\Repositories\PropertyRepositoryInterface;
use Modules\Booking\DTOs\BookingDTO;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;

class BookingService extends BaseService
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private PropertyRepositoryInterface $propertyRepository
    ) {}

    /**
     * Guarantee atomic processing using explicit Overlap and DTO architectures.
     */
    public function createBooking(BookingDTO $dto): Booking
    {
        return $this->executeInTransaction(function () use ($dto) {
            $property = $this->propertyRepository->find($dto->propertyId);
            
            if (!$property) {
                throw new Exception("Property not found.");
            }

            // Lock the property row to serialize concurrent booking attempts
            DB::table('properties')->where('id', $property->id)->lockForUpdate()->get();

            // 1. Check availability
            $isOverlap = $this->bookingRepository->checkOverlap(
                $dto->propertyId, 
                $dto->checkInDate, 
                $dto->checkOutDate
            );

            // 2. If conflict -> throw exception
            if ($isOverlap) {
                throw new Exception("Property is not available for requested dates.");
            }

            $checkIn = Carbon::parse($dto->checkInDate);
            $checkOut = Carbon::parse($dto->checkOutDate);
            $nights = $checkIn->diffInDays($checkOut);
            
            $totalPrice = $nights * $property->price_per_night;

            // 3. Create booking
            $booking = $this->bookingRepository->create([
                'property_id' => $property->id,
                'user_id' => $dto->userId,
                'check_in_date' => $dto->checkInDate,
                'check_out_date' => $dto->checkOutDate,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            return $booking->load(['property', 'user']);
        });
    }

    public function getUserBookings(string $userId)
    {
        return $this->bookingRepository->with(['property', 'property.primaryImage'])->findBy(['user_id' => $userId]);
    }

    public function getPropertyAvailability(string $propertyId, string $startDate, string $endDate)
    {
        return $this->bookingRepository->getUnavailableDates($propertyId, $startDate, $endDate);
    }

    /* ── Host-scoped helpers ─────────────────────────────────── */

    public function countActiveByHost(string $hostId): int
    {
        return $this->bookingRepository->newHostQuery($hostId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
    }

    public function countPendingByHost(string $hostId): int
    {
        return $this->bookingRepository->newHostQuery($hostId)
            ->where('status', 'pending')
            ->count();
    }

    public function revenueThisMonthByHost(string $hostId): float
    {
        return (float) $this->bookingRepository->newHostQuery($hostId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
    }

    public function revenueGrowthPercentByHost(string $hostId): int
    {
        $thisMonth = $this->revenueThisMonthByHost($hostId);
        
        $lastMonth = (float) $this->bookingRepository->newHostQuery($hostId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_price');

        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }

        return (int) round((($thisMonth - $lastMonth) / $lastMonth) * 100);
    }

    public function recentByHost(string $hostId, int $limit = 8)
    {
        return $this->bookingRepository->newHostQuery($hostId)
            ->with(['user', 'property'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
