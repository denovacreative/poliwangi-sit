<?php

use App\Http\Controllers\Api\Portal\AcademicCalendarMonitoringController;
use App\Http\Controllers\Api\Student\Academic\StudentAchievementController;
use App\Http\Controllers\Api\Student\Academic\StudentActivityController;
use App\Http\Controllers\Api\Student\Academic\StudentCourseController;
use App\Http\Controllers\Api\Student\Academic\StudentCurriculumController;
use App\Http\Controllers\Api\Student\Academic\StudentHerRegistrationController;
use App\Http\Controllers\Api\Student\Profile\StudentProfileController;
use App\Http\Controllers\Api\Student\Schedule\StudentScheduleController;
use App\Http\Controllers\Api\Student\Dashboard\StudentDashboardController;
use App\Http\Controllers\Api\Student\Schedule\AnnouncementController;
use App\Http\Controllers\Api\Student\Academic\StudentGradeController;
use App\Http\Controllers\Api\Student\Academic\StudentPresenceController;
use App\Http\Controllers\Api\Student\Schedule\StudentScheduleWeeklyController;
use App\Http\Controllers\Api\Student\FinalLevel\ThesisController;
use App\Http\Controllers\Api\Student\StudentSemesterStatusController;
use App\Http\Controllers\Api\Student\StudyResult\CardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // route print
    Route::get('student/print/study-result/cards', [CardController::class, 'printKhs']);
    Route::get('student/print/study-result/transcript', [CardController::class, 'printTranscript']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('student')->group(function () {
            // Profile
            Route::prefix('profile')->group(function () {
                Route::get('', [StudentProfileController::class, 'index'])->name('student.profile');
                Route::post('/update', [StudentProfileController::class, 'update']);
            });
            // Schedule
            Route::prefix('schedule')->group(function () {
                Route::prefix('schedule-semesters')->middleware('can:read-student-schedule-semester')->group(function () {
                    Route::get('', [StudentScheduleController::class, 'index'])->name('student.schedule.schedule-semester');
                });
                Route::get('schedule-weekly', [StudentScheduleWeeklyController::class, 'index'])->name('student.schedule.weekly-schedule');

                // Announcements
                Route::prefix('announcements')->middleware('can:read-student-announcements')->group(function () {
                    Route::get('', [AnnouncementController::class, 'index']);
                    Route::get('{announcement}/detail', [AnnouncementController::class, 'detail']);
                });

                // Calendar
                Route::prefix('calendars')->middleware('can:read-student-calendars')->group(function () {
                    Route::get('', [AcademicCalendarMonitoringController::class, 'index']);
                });
            });
            // Academic
            Route::prefix('academic')->group(function () {
                Route::prefix('student-courses')->middleware('can:read-student-krs')->group(function () {
                    Route::get('', [StudentCourseController::class, 'index'])->name('student.academic.student-course');
                });
                Route::prefix('student-curriculums')->middleware('can:read-student-curriculums')->group(function () {
                    Route::get('', [StudentCurriculumController::class, 'index'])->name('student.academic.student-curriculum');
                });
                Route::prefix('student-activities')->middleware('can:read-student-activities')->group(function () {
                    Route::get('', [StudentActivityController::class, 'index'])->name('student.academic.student-activities');
                });
                Route::prefix('her-registrations')->middleware('can:read-student-her-registration')->group(function () {
                    Route::get('', [StudentHerRegistrationController::class, 'index'])->name('student.her-registration');
                    Route::get('data', [StudentHerRegistrationController::class, 'getHerRegistrationData'])->name('student.her-registration-data');
                    Route::post('', [StudentHerRegistrationController::class, 'store'])->name('student.her-registration.store');
                });

                // Grade
                Route::prefix('grade')->middleware('can:read-student-scores')->group(function () {
                    Route::get('get-academic-periods', [StudentGradeController::class, 'getAcademicPeriod'])->name('student.grade.getAcademicPeriod');
                    Route::get('{academicPeriod}/get-score', [StudentGradeController::class, 'getScore'])->name('student.grade.get-score');
                });

                // Presences
                Route::prefix('presences')->middleware('can:read-student-presences')->group(function () {
                    Route::get('', [StudentPresenceController::class, 'index'])->name('student.academic.presences');
                    Route::get('{collegeClass}', [StudentPresenceController::class, 'detail'])->name('student.academic.presences.detail');
                });

                Route::prefix('achievements')->middleware('can:read-student-activities')->group(function () {
                    Route::get('', [StudentAchievementController::class, 'index'])->name('student.academic.achievements');
                });
            });
            // Study Result
            Route::prefix('study-result')->group(function () {
                Route::prefix('cards')->middleware('can:read-student-study-result-cards')->group(function () {
                    Route::get('', [CardController::class, 'index'])->name('student.study-result.cards');
                });
                Route::prefix('transcript')->middleware('can:read-student-transcript')->group(function() {
                    Route::get('', [CardController::class, 'studentTranscript'])->name('student.transcript');
                });
            });
            // Final Level
            Route::prefix('final-level')->group(function () {
                Route::prefix('theses')->group(function () {
                    Route::get('', [ThesisController::class, 'index'])->name('student.these');
                    Route::post('', [ThesisController::class, 'store'])->name('student.these.store');
                    Route::delete('{these}', [ThesisController::class, 'destroy'])->name('student.these.delete');
                    Route::post('{these}', [ThesisController::class, 'update'])->name('student.these.update');
                    Route::get('{these}', [ThesisController::class, 'show'])->name('student.these.show');
                });
            });

            // AKM
            Route::prefix('semester-status')->middleware('can:read-student-semester-status')->group(function () {
                Route::get('', [StudentSemesterStatusController::class, 'index'])->name('student.semester-status');
            });

            // dashboard
            Route::prefix('dashboard')->middleware('can:read-student-dashboard')->group(function () {
                Route::get('', [StudentDashboardController::class, 'index']);
                Route::get('get-class-schedules', [StudentDashboardController::class, 'getClassSchedules']);
            });

            // Announcements
            Route::prefix('announcements')->middleware('can:read-student-announcements')->group(function () {
                Route::get('', [AnnouncementController::class, 'index']);
                Route::get('{announcement}/detail', [AnnouncementController::class, 'detail']);
            });

            // Grade
            Route::prefix('grade')->middleware('can:read-student-scores')->group(function () {
                // Route::get('get-academic-periods', [StudentGradeController::class, 'getAcademicPeriod'])->name('student.grade.getAcademicPeriod');
                Route::get('{academicPeriod}/get-score', [StudentGradeController::class, 'getScore'])->name('student.grade.get-score');
            });
        });
    });
});
