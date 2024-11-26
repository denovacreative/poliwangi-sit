<?php

use App\Http\Controllers\Api\Lecturer\GeneralLecturerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('lecturer')->group(function () {
            Route::prefix('general')->group(function () {
                Route::get('lecturer-info', [GeneralLecturerController::class, 'index']);
            });
        });
    });
});
