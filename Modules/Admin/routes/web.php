<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminBookingController;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\AdminPropertyController;
use Modules\Admin\Http\Controllers\AdminUserController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}', [AdminPropertyController::class, 'show'])->name('properties.show');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('properties.reject');
    Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::post('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/complete', [AdminBookingController::class, 'complete'])->name('bookings.complete');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
});
