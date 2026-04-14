<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes are handled dynamically by Modules/Auth/routes/api.php

    // Bookings
    Route::middleware('throttle:bookings')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus']); // Confirm/Cancel
    });

    // Chat
    Route::middleware('throttle:chat')->group(function () {
        Route::get('/chat/{userId}', [ChatController::class, 'index']);
        Route::post('/chat', [ChatController::class, 'store']);
    });
});
