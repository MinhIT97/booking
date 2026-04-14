<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingStatusRequest;
use Modules\Booking\Services\BookingService;
use App\Http\Resources\BookingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Exception;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(): AnonymousResourceCollection
    {
        $bookings = $this->bookingService->getUserBookings(request()->user()->id);
        
        return BookingResource::collection($bookings);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->bookProperty($request->validated(), $request->user()->id);

            return response()->json([
                'message' => 'Booking created successfully',
                'data' => new BookingResource($booking)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function updateStatus(UpdateBookingStatusRequest $request, string $id): JsonResponse|BookingResource
    {
        $booking = $this->bookingService->updateBookingStatus($id, $request->validated('status'));

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return new BookingResource($booking);
    }
}
