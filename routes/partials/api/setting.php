<?php

use App\Http\Controllers\Api\Setting\AcademicYearController;
use App\Http\Controllers\Api\Setting\SemesterController;
use App\Http\Controllers\Api\Setting\SettingController;
use App\Http\Controllers\Api\Setting\StudyProgramSettingController;
use App\Models\Setting;
use App\Models\StudyProgramSetting;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('setting')->group(function(){
            Route::prefix('setting-application')->middleware('can:read-dashboard')->group(function(){
                Route::get('', [SettingController::class, 'getSetting'])->name('setting.application');
                Route::post('updateSetting', [SettingController::class, 'updateSetting'])->name('setting.application');
            });

            Route::prefix('semesters')->middleware('can:read-semesters')->group(function(){
                Route::get('', [SemesterController::class, 'index'])->name('setting.academic-period');
                Route::post('', [SemesterController::class, 'store'])->middleware('can:create-semesters')->name('setting.academic-period.store');
                Route::delete('{academicPeriod}', [SemesterController::class, 'destroy'])->middleware('can:delete-semesters')->name('setting.academic-period.destroy');
                Route::post('{academicPeriod}', [SemesterController::class, 'update'])->middleware('can:update-semesters')->name('setting.academic-period.update');
                Route::get('{academicPeriod}/status', [SemesterController::class, 'updateStatus'])->middleware('can:update-semesters')->name('setting.academic-period.update-status');
                Route::get('{academicPeriod}/set-active', [SemesterController::class, 'setActive'])->middleware('can:update-semesters')->name('setting.academic-period.set-active');
                Route::get('{academicPeriod}', [SemesterController::class, 'show'])->name('setting.academic-period.show');
            });

            Route::prefix('academic-years')->middleware('can:read-academic-years')->group(function () {
                Route::get('', [AcademicYearController::class, 'index'])->name('lecture.academic-year');
                Route::post('', [AcademicYearController::class, 'store'])->middleware('can:create-academic-years')->name('lecture.academic-year.store');
                Route::delete('{academicYear}', [AcademicYearController::class, 'destroy'])->middleware('can:delete-academic-years')->name('lecture.academic-year.destroy');
                Route::post('{academicYear}', [AcademicYearController::class, 'update'])->middleware('can:update-academic-years')->name('lecture.academic-year.update');
                Route::get('{academicYear}', [AcademicYearController::class, 'show'])->name('lecture.academic-year.show');
            });

            Route::prefix('study-programs')->middleware('can:read-study-program-settings')->group(function() {
                Route::get('', [StudyProgramSettingController::class, 'index'])->name('setting.study-programs');
                Route::post('', [StudyProgramSettingController::class, 'update'])->name('setting.study-programs.update');
                Route::post('{studyProgramId}', [StudyProgramSettingController::class, 'updateDetail'])->name('setting.study-programs.update-detail');
                Route::get('{studyProgramId}', [StudyProgramSettingController::class, 'show'])->name('setting.study-programs.show');
            });
        });
    });
});
