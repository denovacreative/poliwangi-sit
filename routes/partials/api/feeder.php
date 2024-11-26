<?php

use App\Http\Controllers\Api\Feeder\CollegeClassFeederController;
use App\Http\Controllers\Api\Feeder\CourseFeederController;
use App\Http\Controllers\Api\Feeder\CurriculumFeederController;
use App\Http\Controllers\Api\Feeder\EmployeeFeederController;
use App\Http\Controllers\Api\Feeder\FeederConnectionController;
use App\Http\Controllers\Api\Feeder\GraduationFeederController;
use App\Http\Controllers\Api\Feeder\ReadFeederController;
use App\Http\Controllers\Api\Feeder\ReferenceFeederController;
use App\Http\Controllers\Api\Feeder\ScoreFeederController;
use App\Http\Controllers\Api\Feeder\StudentFeederController;
use App\Http\Controllers\Api\Feeder\Truncate\TruncateController;
use App\Models\CollegeClass;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('truncate')->group(function () {
            Route::post('', [TruncateController::class, 'truncate'])->name('truncate');
        });
        Route::prefix('feeder')->middleware('can:read-feeders')->group(function () {
            Route::prefix('connection')->group(function () {
                Route::get('', [FeederConnectionController::class, 'connection']);
                Route::post('', [FeederConnectionController::class, 'connection']);
            });
            Route::prefix('ref')->group(function () {
                Route::post('sync', [ReferenceFeederController::class, 'sync']);
                Route::prefix('study-program')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getStudyPrograms']);
                });
            });
            //score
            Route::prefix('score')->group(function () {
                Route::post('sync', [ScoreFeederController::class, 'sync']);
                //score scale
                Route::prefix('score-scale')->group(function () {
                    Route::post('sync', [ScoreFeederController::class, 'sync']);
                    Route::get('', [ReadFeederController::class, 'getScoreScales']);
                });
            });
            //curriculum
            Route::prefix('curriculum')->group(function () {
                Route::post('sync', [CurriculumFeederController::class, 'sync']);
                Route::get('', [ReadFeederController::class, 'getCurriculums']);
            });
            //course
            Route::prefix('course')->group(function () {
                Route::post('sync', [CourseFeederController::class, 'sync']);
                Route::get('', [ReadFeederController::class, 'getCourses']);
                //course-curriculum
                Route::prefix('course-curriculum')->group(function () {
                    Route::post('sync', [CourseFeederController::class, 'sync']);
                    Route::get('', [ReadFeederController::class, 'getCourseCurriculums']);
                });
            });
            //student
            Route::prefix('student')->group(function () {
                Route::post('sync', [StudentFeederController::class, 'sync']);
                Route::get('', [ReadFeederController::class, 'getStudents']);
                //student-graduation
                Route::prefix('graduation')->group(function () {
                    Route::post('sync', [GraduationFeederController::class, 'sync']);
                    //get data graduation
                    Route::get('', [ReadFeederController::class, 'getGraduations']);
                });
                // get data student activity
                Route::prefix('student-activity')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getStudentActivities']);
                });
                // get student activity member
                Route::prefix('student-activity-member')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getStudentActivityMembers']);
                });
                //get student-activity-supervisor
                Route::prefix('student-activity-supervisor')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getStudentActivitySupervisors']);
                });
            });
            //employee/dosen
            Route::prefix('employee')->group(function () {
                Route::post('sync', [EmployeeFeederController::class, 'sync']);
                //get employee/dosen
                Route::get('', [ReadFeederController::class, 'getEmployees']);
                //teaching lecturer/dosen pengajar
                Route::prefix('teaching-lecturer')->group(function () {
                    Route::post('sync', [EmployeeFeederController::class, 'sync']);
                });
            });

            Route::prefix('lecture')->group(function () {
                Route::post('sync', [CollegeClassFeederController::class, 'sync']);
                Route::prefix('college-class')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getCollegeClasses']);
                });
                Route::prefix('student-college-activity')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getStudentCollegeActivities']);
                });
                Route::prefix('class-participant')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getClassParticipants']);
                });
                Route::prefix('score')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getScores']);
                });
                Route::prefix('teaching-lecturer')->group(function () {
                    Route::get('', [ReadFeederController::class, 'getTeachingLecturers']);
                });
            });
        });
    });
});
