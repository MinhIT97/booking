<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('admin/summary', [AdminController::class, 'summary'])->name('admin.summary');
});
