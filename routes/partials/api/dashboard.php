<?php

use App\Http\Controllers\Api\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('dash', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('', [DashboardController::class, 'index'])->name('dashboard.index');
        });
    });
});