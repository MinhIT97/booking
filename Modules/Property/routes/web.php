<?php

use Illuminate\Support\Facades\Route;
use Modules\Property\Http\Controllers\PropertyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('properties', PropertyController::class)->names('property');
});
