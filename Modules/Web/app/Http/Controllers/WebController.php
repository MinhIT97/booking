<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Property\Services\PropertyService;
use Modules\Booking\Services\BookingService;
use Modules\Property\Enums\PropertyStatus;
use Modules\Booking\Models\Booking;
use Modules\Booking\Enums\BookingStatus;

class WebController extends Controller
{
    public function __construct(
        private PropertyService $propertyService,
        private BookingService $bookingService
    ) {}

    /**
     * Display the professional web landing page.
     */
    public function index(Request $request)
    {
        $categories = $this->propertyService->getPropertyTypes();

        $properties = $this->propertyService->getAllProperties([
            'status' => PropertyStatus::Active->value,
            'type_slug' => $request->query('type'),
            ...$request->all()
        ]);

        $featured = $properties->take(3);
        $villas = $this->propertyService->getAllProperties([
            'type_slug' => 'villa', 
            'status' => PropertyStatus::Active->value
        ])->take(4);

        return view('web::index', compact('properties', 'featured', 'categories', 'villas'));
    }

    /**
     * Display search results.
     */
    public function search(Request $request)
    {
        $categories = $this->propertyService->getPropertyTypes();
        
        $properties = $this->propertyService->getAllProperties([
            'status' => PropertyStatus::Active->value,
            'location' => $request->query('location'),
            'type_slug' => $request->query('type'),
            ...$request->all()
        ]);

        return view('web::properties.search', compact('properties', 'categories'));
    }

    /**
     * Display property detail.
     */
    public function show(string $slug)
    {
        $property = $this->propertyService->getPropertyBySlug($slug);

        if (!$property) {
            abort(404);
        }

        // Fetch booked dates to disable in calendar
        $bookedDates = $property->bookings()
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->get(['check_in_date', 'check_out_date'])
            ->map(fn($b) => [
                'from' => $b->check_in_date->format('Y-m-d'),
                'to' => $b->check_out_date->format('Y-m-d')
            ])->values()->toArray();

        // Fetch related properties or trending for sidebar
        $related = $this->propertyService->getAllProperties([
            'status' => PropertyStatus::Active->value,
        ])->where('slug', '!=', $slug)->take(4);

        return view('web::properties.show', compact('property', 'related', 'bookedDates'));
    }

    /**
     * Display booking/checkout page.
     */
    public function booking(Request $request, string $slug)
    {
        $property = $this->propertyService->getPropertyBySlug($slug);

        if (!$property) {
            abort(404);
        }

        // Parse query params
        $dates = $request->query('dates'); // Expecting "YYYY-MM-DD to YYYY-MM-DD"
        $guests = (int) $request->query('guests', 1);

        $checkIn = null;
        $checkOut = null;
        $nights = 0;

        if ($dates && str_contains($dates, ' to ')) {
            [$start, $end] = explode(' to ', $dates);
            $checkIn = \Carbon\Carbon::parse($start);
            $checkOut = \Carbon\Carbon::parse($end);
            $nights = $checkIn->diffInDays($checkOut);
        }

        return view('web::bookings.create', compact('property', 'dates', 'guests', 'nights', 'checkIn', 'checkOut'));
    }

    /**
     * Handle booking submission.
     */
    public function storeBooking(Request $request)
    {
        // 1. Precise date extraction favoring hidden fields from create.blade.php
        $data = $request->only(['property_id', 'dates', 'check_in_date', 'check_out_date', 'guests', 'message']);

        // Fallback for direct API/range string submissions
        if (empty($data['check_in_date']) && !empty($data['dates']) && str_contains($data['dates'], ' to ')) {
            [$start, $end] = explode(' to ', $data['dates']);
            $data['check_in_date'] = $start;
            $data['check_out_date'] = $end;
        }

        $request->merge($data);

        $request->validate([
            'property_id' => 'required|uuid|exists:properties,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            $booking = $this->bookingService->bookProperty($request->all(), auth()->id());

            return redirect()->route('landing')->with('success', 'Booking confirmed! A confirmation email has been sent to your inbox.');
        } catch (\Exception $e) {
            // Log error for debugging if needed
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
