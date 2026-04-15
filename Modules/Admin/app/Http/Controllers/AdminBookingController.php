<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Services\AdminBookingService;
use Modules\Booking\Enums\BookingStatus;

class AdminBookingController extends Controller
{
    public function __construct(protected AdminBookingService $bookingService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search']);
        $bookings = $this->bookingService->getBookingList($filters);

        return view('admin::admin.bookings.index', compact('bookings'));
    }

    public function show(string $id)
    {
        $booking = $this->bookingService->getBooking($id);

        if (!$booking) {
            return redirect()->route('admin.bookings.index')->with('error', 'Booking not found.');
        }

        return view('admin::admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed,1,2,3,4'],
        ]);

        $success = $this->bookingService->updateStatus($id, $validated['status']);

        return $success
            ? back()->with('success', 'Booking status updated successfully.')
            : back()->with('error', 'Failed to update booking status.');
    }

    public function confirm(string $id)
    {
        return $this->setStatus($id, BookingStatus::Confirmed);
    }

    public function cancel(string $id)
    {
        return $this->setStatus($id, BookingStatus::Cancelled);
    }

    public function complete(string $id)
    {
        return $this->setStatus($id, BookingStatus::Completed);
    }

    public function destroy(string $id)
    {
        $success = $this->bookingService->deleteBooking($id);

        return $success
            ? redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.')
            : back()->with('error', 'Failed to delete booking.');
    }

    private function setStatus(string $id, BookingStatus $status)
    {
        $success = $this->bookingService->updateStatus($id, $status);

        return $success
            ? back()->with('success', 'Booking status updated successfully.')
            : back()->with('error', 'Failed to update booking status.');
    }
}
