<?php

use App\Http\Controllers\Api\StudentAffairs\AchievementController;
use App\Http\Controllers\Api\StudentAffairs\AchievementGroupController;
use App\Http\Controllers\Api\StudentAffairs\AchievementTypeController;
use App\Http\Controllers\Api\StudentAffairs\DiplomaCompanionController;
use App\Http\Controllers\Api\StudentAffairs\ScholarshipController;
use App\Http\Controllers\Api\StudentAffairs\ScholarshipTypeController;
use App\Http\Controllers\Api\StudentAffairs\StudentScholarshipController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('student-affair')->group(function () {

            Route::prefix('achievement-groups')->middleware('can:read-achievement-groups')->group(function () {
                Route::get('', [AchievementGroupController::class, 'index'])->name('student-affairs.achievement-group');
                Route::post('', [AchievementGroupController::class, 'store'])->middleware('can:create-achievement-groups')->name('student-affairs.achievement-group.store');
                Route::delete('{achievementGroup}', [AchievementGroupController::class, 'destroy'])->middleware('can:delete-achievement-groups')->name('student-affairs.achievement-group.destroy');
                Route::post('{achievementGroup}', [AchievementGroupController::class, 'update'])->middleware('can:update-achievement-groups')->name('student-affairs.achievement-group.update');
                Route::get('{achievementGroup}', [AchievementGroupController::class, 'show'])->name('student-affairs.achievement-group.show');
                Route::get('{achievementGroup}/status', [AchievementGroupController::class, 'updateStatus'])->name('student-affairs.achievement-group.update-status');
            });
            Route::prefix('achievement-types')->middleware('can:read-achievement-types')->group(function () {
                Route::get('', [AchievementTypeController::class, 'index'])->name('student-affairs.achievement-type');
                Route::post('', [AchievementTypeController::class, 'store'])->middleware('can:create-achievement-types')->name('student-affairs.achievement-type.store');
                Route::delete('{achievementType}', [AchievementTypeController::class, 'destroy'])->middleware('can:delete-achievement-types')->name('student-affairs.achievement-type.destroy');
                Route::post('{achievementType}', [AchievementTypeController::class, 'update'])->middleware('can:update-achievement-types')->name('student-affairs.achievement-type.update');
                Route::get('{achievementType}', [AchievementTypeController::class, 'show'])->name('student-affairs.achievement-type.show');
            });
            Route::prefix('scholarship-types')->middleware('can:read-scholarship-types')->group(function () {
                Route::get('', [ScholarshipTypeController::class, 'index'])->name('student-affairs.scholarship-type');
                Route::post('', [ScholarshipTypeController::class, 'store'])->middleware('can:create-scholarship-types')->name('student-affairs.scholarship-type.store');
                Route::delete('{scholarshipType}', [ScholarshipTypeController::class, 'destroy'])->middleware('can:delete-scholarship-types')->name('student-affairs.scholarship-type.destroy');
                Route::post('{scholarshipType}', [ScholarshipTypeController::class, 'update'])->middleware('can:update-scholarship-types')->name('student-affairs.scholarship-type.update');
                Route::get('{scholarshipType}', [ScholarshipTypeController::class, 'show'])->name('student-affairs.scholarship-type.show');
            });
            Route::prefix('scholarships')->middleware('can:read-scholarships')->group(function () {
                Route::get('', [ScholarshipController::class, 'index'])->name('student-affairs.scholarship');
                Route::post('', [ScholarshipController::class, 'store'])->middleware('can:create-scholarships')->name('student-affairs.scholarship.store');
                Route::delete('{scholarship}', [ScholarshipController::class, 'destroy'])->middleware('can:delete-scholarships')->name('student-affairs.scholarship.destroy');
                Route::post('{scholarship}', [ScholarshipController::class, 'update'])->middleware('can:update-scholarships')->name('student-affairs.scholarship.update');
                Route::get('{scholarship}', [ScholarshipController::class, 'show'])->name('student-affairs.scholarship.show');

                Route::prefix('{scholarship}/student-scholarship')->group(function () {
                    Route::get('', [StudentScholarshipController::class, 'index'])->name('student-affairs.student-scholarship');
                    Route::post('', [StudentScholarshipController::class, 'store'])->middleware('can:create-scholarships')->name('student-affairs.student-scholarship.store');
                    Route::delete('{studentScholarship}', [StudentScholarshipController::class, 'destroy'])->middleware('can:delete-scholarships')->name('student-affairs.student-scholarship.destroy');
                    Route::post('{studentScholarship}', [StudentScholarshipController::class, 'update'])->middleware('can:update-scholarships')->name('student-affairs.student-scholarship.update');
                    Route::get('{studentScholarship}', [StudentScholarshipController::class, 'show'])->name('student-affairs.student-scholarship.show');
                    Route::get('{studentScholarship}/status', [StudentScholarshipController::class, 'updateStatus'])->middleware('can:update-scholarships')->name('student-affairs.student-scholarship.update-status');
                });
            });
            Route::prefix('diploma-companions')->middleware('can:read-diploma-companions')->group(function () {
                Route::get('', [DiplomaCompanionController::class, 'index'])->name('student-affairs.diploma-companion');
                Route::post('', [DiplomaCompanionController::class, 'store'])->middleware('can:create-diploma-companions')->name('student-affairs.diploma-companion.store');
                Route::post('{diplomaCompanion}', [DiplomaCompanionController::class, 'update'])->middleware('can:update-diploma-companions')->name('student-affairs.diploma-companion.update');
                Route::get('{diplomaCompanion}', [DiplomaCompanionController::class, 'show'])->name('student-affairs.diploma-companion.show');
            });
            // Achievement Routing
            Route::prefix('achievements')->middleware('can:read-achievement')->group(function () {
                Route::get('', [AchievementController::class, 'index'])->name('student-affairs.achievement');
                Route::get('{achievement}/show', [AchievementController::class, 'show'])->name('student-affairs.achievement.show');
                Route::post('{achievement}/update', [AchievementController::class, 'update'])->name('student-affairs.achievement.update');
                Route::post('', [AchievementController::class, 'store'])->name('student-affairs.achievement.store')->middleware('can:create-achievement');
                Route::delete('{achievement}', [AchievementController::class, 'destroy'])->name('student-affairs.achievement.destroy')->middleware('can:delete-achievement');
            });
        });
    });
});
