<?php

use Illuminate\Support\Facades\Route;
use Modules\Mobile\Http\Controllers\MobileController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('mobiles', MobileController::class)->names('mobile');
});
