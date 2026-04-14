<?php

namespace Modules\Mobile\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Property\Services\PropertyService;
use Modules\Booking\Services\BookingService;
use Modules\Mobile\Http\Requests\StoreBookingRequest;
use Modules\Booking\DTOs\BookingDTO;

class MobileController extends Controller
{
    public function __construct(
        private PropertyService $propertyService,
        private BookingService $bookingService
    ) {}

    public function home(Request $request)
    {
        $properties = $this->propertyService->getAllProperties($request->all());
        return view('mobile::home', compact('properties'));
    }

    public function detail($id)
    {
        $property = $this->propertyService->getPropertyById($id);
        return view('mobile::detail', compact('property'));
    }

    public function booking($property_id)
    {
        $property = $this->propertyService->getPropertyById($property_id);
        return view('mobile::booking', compact('property'));
    }

    public function storeBooking(StoreBookingRequest $request)
    {
        try {
            $dto = BookingDTO::fromRequest($request);
            $this->bookingService->createBooking($dto);
            
            return redirect()->route('mobile.bookings.index')->with('success', 'Booking confirmed!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function trips()
    {
        $bookings = $this->bookingService->getUserBookings(auth()->id());
        return view('mobile::bookings', compact('bookings'));
    }

    public function profile()
    {
        return view('mobile::profile');
    }
}
