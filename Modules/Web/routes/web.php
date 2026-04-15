<?php

use Illuminate\Support\Facades\Route;
use Modules\Web\Http\Controllers\WebController;

Route::get('/properties/{id}', [WebController::class, 'show'])->name('properties.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/properties/{id}/booking', [WebController::class, 'booking'])->name('bookings.create');
    Route::post('/bookings', [WebController::class, 'storeBooking'])->name('bookings.store');
});
