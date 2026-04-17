<?php

use Illuminate\Support\Facades\Route;
use Modules\Web\Http\Controllers\WebController;

Route::get('/search', [WebController::class, 'search'])->name('properties.search');
Route::get('/properties/{slug}', [WebController::class, 'show'])->name('properties.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/properties/{slug}/booking', [WebController::class, 'booking'])->name('bookings.create');
    Route::post('/bookings', [WebController::class, 'storeBooking'])->name('bookings.store');
});
