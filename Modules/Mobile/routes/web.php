<?php

use Illuminate\Support\Facades\Route;
use Modules\Mobile\Http\Controllers\MobileController;

Route::prefix('m')->name('mobile.')->group(function () {
    Route::get('/', [MobileController::class, 'home'])->name('home');
    Route::get('/property/{id}', [MobileController::class, 'detail'])->name('detail');
    
    Route::middleware('auth')->group(function () {
        Route::get('/booking/{property_id}', [MobileController::class, 'booking'])->name('booking');
        Route::post('/booking', [MobileController::class, 'storeBooking'])->name('booking.store');
        Route::get('/trips', [MobileController::class, 'trips'])->name('bookings.index');
        Route::get('/profile', [MobileController::class, 'profile'])->name('profile');
    });
    
    // Fallback for wishlist etc (placeholder)
    Route::get('/wishlist', fn() => redirect()->route('mobile.home'))->name('bookings');
});
