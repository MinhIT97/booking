<?php

namespace Modules\Host\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Property\Services\PropertyService;
use Modules\Booking\Services\BookingService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PropertyService $propertyService,
        private readonly BookingService  $bookingService,
    ) {}

    public function index(): View
    {
        $hostId = auth()->id();

        // ── Stat aggregates ──────────────────────────────────────
        $stats = [
            'total_properties'       => $this->propertyService->countByHost($hostId),
            'new_properties_this_month' => $this->propertyService->countByHostThisMonth($hostId),
            'active_bookings'        => $this->bookingService->countActiveByHost($hostId),
            'pending_bookings'       => $this->bookingService->countPendingByHost($hostId),
            'monthly_revenue'        => $this->bookingService->revenueThisMonthByHost($hostId),
            'revenue_growth'         => $this->bookingService->revenueGrowthPercentByHost($hostId),
            'average_rating'         => $this->propertyService->averageRatingByHost($hostId),
            'total_reviews'          => $this->propertyService->totalReviewsByHost($hostId),
        ];

        // ── Recent bookings (last 8) ──────────────────────────────
        $recentBookings = $this->bookingService->recentByHost($hostId, limit: 8);

        // ── Top properties by booking count ──────────────────────
        $topProperties = $this->propertyService->topByHost($hostId, limit: 5);

        return view('host::dashboard', compact('stats', 'recentBookings', 'topProperties'));
    }
}
