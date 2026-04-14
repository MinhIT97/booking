<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Booking\DTOs\BookingDTO;
use Modules\Booking\Http\Requests\StoreBookingRequest;
use Modules\Booking\Services\BookingService;
use Modules\Booking\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use Exception;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    /**
     * Store Endpoint using ultra thin footprint.
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $dto = BookingDTO::fromRequest($request);
            $booking = $this->bookingService->createBooking($dto);

            return new BookingResource($booking);
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Return explicitly cached unavailability window arrays tightly scoped cleanly outside the main Booking execution timeline.
     */
    public function availability(string $propertyId, Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        try {
            $unavailableDates = $this->bookingService->getPropertyAvailability(
                $propertyId,
                $validated['start_date'],
                $validated['end_date']
            );

            return response()->json(['unavailable_dates' => $unavailableDates], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
