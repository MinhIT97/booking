<?php

use Illuminate\Support\Facades\Route;
use Modules\Host\Http\Controllers\DashboardController;
use Modules\Host\Http\Controllers\PropertyController;

/*
|--------------------------------------------------------------------------
|  Host Module — Web Routes
|--------------------------------------------------------------------------
|  All routes require the user to be authenticated (auth middleware).
|  Named with "host." prefix so sidebar active-detection works cleanly.
|
|  Prefix  : /host
|  Name    : host.*
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('host')
    ->name('host.')
    ->group(function () {

        /* ── Dashboard ──────────────────────────────────── */
        Route::get('/', [DashboardController::class, 'index'])
             ->name('dashboard');

        /* ── Properties (full resource) ─────────────────── */
        // name() prefix from the group already adds "host." so we only
        // need the bare suffix — avoids "host.host.properties.*" doubling.
        Route::resource('properties', PropertyController::class)
             ->names('properties');   // → host.properties.{index,create,store,show,edit,update,destroy}

        /* ── Stub routes (ready to implement) ───────────── */
        Route::get('bookings',  fn () => view('host::bookings.index'))->name('bookings.index');
        Route::get('earnings',  fn () => view('host::earnings.index'))->name('earnings.index');
        Route::get('reviews',   fn () => view('host::reviews.index'))->name('reviews.index');
        Route::get('settings',  fn () => view('host::settings.index'))->name('settings.index');
    });
