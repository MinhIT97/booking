<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Property\Services\PropertyService;
use Modules\Booking\Services\BookingService;
use Modules\Property\Enums\PropertyStatus;
use Exception;

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
     * Display property detail.
     */
    public function show(string $id)
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            abort(404);
        }

        // Fetch related properties or trending for sidebar
        $related = $this->propertyService->getAllProperties([
            'status' => PropertyStatus::Active->value,
        ])->where('id', '!=', $id)->take(4);

        return view('web::properties.show', compact('property', 'related'));
    }

    /**
     * Display booking/checkout page.
     */
    public function booking(string $id)
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            abort(404);
        }

        return view('web::bookings.create', compact('property'));
    }

    /**
     * Handle booking submission.
     */
    public function storeBooking(Request $request)
    {
        $request->validate([
            'property_id' => 'required|uuid|exists:properties,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
        ]);

        try {
            $booking = $this->bookingService->bookProperty($request->all(), auth()->id());

            return redirect()->route('web.index')->with('success', 'Booking confirmed successfully!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
