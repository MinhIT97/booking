<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

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
