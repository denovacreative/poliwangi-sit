<?php

use App\Http\Controllers\Api\Student\Academic\StudentCourseController;
use App\Http\Controllers\Api\Student\GeneralStudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('student')->group(function () {
            Route::prefix('general')->group(function () {
                Route::get('student-info', [GeneralStudentController::class, 'index']);
                Route::get('student-semester', [GeneralStudentController::class, 'studentSemester']);
            });
        });
    });
});
