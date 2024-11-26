<?php

use App\Http\Controllers\Api\Log\UserAuthLogController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::prefix('logs')->middleware('auth:sanctum')->group(function() {
        Route::get('user-auth-logs', [UserAuthLogController::class, 'index'])->middleware('can:read-user-auth-logs');
    });
});
