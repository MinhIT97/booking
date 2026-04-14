<?php

use Illuminate\Support\Facades\Route;
use Modules\Host\Http\Controllers\HostController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('hosts', HostController::class)->names('host');
});
