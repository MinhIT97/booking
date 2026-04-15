<?php

namespace Modules\Admin\Http\Controllers;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use Modules\Admin\Services\AdminBookingService;
use Modules\Admin\Services\AdminPropertyService;
use Modules\Admin\Services\AdminUserService;
use Modules\Booking\Enums\BookingStatus;
use Modules\Property\Enums\PropertyStatus;

class AdminController extends Controller
{
    public function __construct(
        protected AdminUserService $userService,
        protected AdminPropertyService $propertyService,
        protected AdminBookingService $bookingService,
    ) {}

    public function index()
    {
        $stats = $this->stats();

        $recentUsers = $this->userService->recent();
        $recentProperties = $this->propertyService->recent();
        $recentBookings = $this->bookingService->recent();

        return view('admin::dashboard', compact('stats', 'recentUsers', 'recentProperties', 'recentBookings'));
    }

    public function settings()
    {
        return view('admin::settings');
    }

    public function summary()
    {
        return response()->json(['data' => $this->stats()]);
    }

    private function stats(): array
    {
        return [
            'active_users' => $this->userService->countByStatus(UserStatus::Active),
            'hosts' => $this->userService->countByRole('host'),
            'draft_properties' => $this->propertyService->countByStatus(PropertyStatus::Draft),
            'active_properties' => $this->propertyService->countByStatus(PropertyStatus::Active),
            'pending_bookings' => $this->bookingService->countByStatus(BookingStatus::Pending),
            'confirmed_bookings' => $this->bookingService->countByStatus(BookingStatus::Confirmed),
            'revenue_total' => $this->bookingService->revenueTotal(),
        ];
    }
}
