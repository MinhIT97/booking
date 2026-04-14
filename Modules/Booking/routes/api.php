<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\BookingController;

Route::prefix('v1')->group(function () {
    Route::get('properties/{property_id}/availability', [BookingController::class, 'availability'])->name('properties.availability');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
});
