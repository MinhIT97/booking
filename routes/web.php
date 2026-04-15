<?php

use Illuminate\Support\Facades\Route;

use Modules\Web\Http\Controllers\WebController;

Route::get('/', [WebController::class, 'index'])->name('landing');
