<?php

use Illuminate\Support\Facades\Route;
use Modules\Property\Http\Controllers\PropertyController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{id}', [PropertyController::class, 'show']);
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::put('/properties/{id}', [PropertyController::class, 'update']);
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);
});
