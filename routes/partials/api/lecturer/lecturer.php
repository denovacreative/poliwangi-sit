<?php

use App\Http\Controllers\Api\Lecture\CollegeClass\ClassScheduleController;
use App\Http\Controllers\Api\Lecture\PresenceController;
use App\Http\Controllers\Api\Lecturer\Announcement\LecturerAnnouncementController;
use App\Http\Controllers\Api\Lecturer\Dashboard\LecturerDashboardController;
use App\Http\Controllers\Api\Lecturer\Guidance\LecturerActivityController;
use App\Http\Controllers\Api\Lecturer\Lecture\LecturerPresenceController;
use App\Http\Controllers\Api\Lecturer\Profile\LecturerProfileController;
use App\Http\Controllers\Api\Lecturer\Schedule\LecturerScheduleController;
use App\Http\Controllers\Api\Lecturer\Guardianship\GuardianshipController;
use App\Http\Controllers\Api\Lecturer\Lecture\LecturerCollegeClassController;
use App\Http\Controllers\Api\Lecturer\Lecture\LecturerStudentGradeController;
use App\Http\Controllers\Api\Lecturer\Schedule\LecturerWeeklyScheduleController;
use App\Http\Controllers\Api\Portal\AcademicCalendarMonitoringController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;



Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('lecturer')->group(function () {
            Route::prefix('dashboard')->middleware('can:read-lecturer-dashboard')->group(function () {
                Route::get('', [LecturerDashboardController::class, 'index']);
                Route::get('/unpresence', [LecturerDashboardController::class, 'getUnpresence']);
                Route::get('/class-schedules', [LecturerDashboardController::class, 'getClassSchedule']);
                Route::post('/realization-class-schedules', [LecturerDashboardController::class, 'realizationClassSchedule']);
                Route::post('/class_rescheduling', [LecturerDashboardController::class, 'classRescheduling']);
                Route::get('/start-class-schedules/{classSchedule}', [LecturerDashboardController::class, 'startClassSchedules']);
            });
            // Schedule
            Route::prefix('schedule')->group(function () {
                Route::prefix('schedule-semesters')->middleware('can:read-lecturer-schedule-semester')->group(function () {
                    Route::get('', [LecturerScheduleController::class, 'index'])->name('lecturer.schedule.schedule-semester');
                });
                // Calendar
                Route::prefix('calendars')->middleware('can:read-lecturer-calendars')->group(function () {
                    Route::get('', [AcademicCalendarMonitoringController::class, 'index']);
                });
                // Weekly Schedules
                Route::prefix('weekly-schedules')->middleware('can:read-lecturer-weekly-schedules')->group(function () {
                    Route::get('', [LecturerWeeklyScheduleController::class, 'index'])->name('lecturer.weekly-schedules');
                });
            });
            // Guidance
            Route::prefix('guidance')->group(function () {
                Route::prefix('activities')->middleware('can:read-lecturer-activity')->group(function () {
                    Route::get('', [LecturerActivityController::class, 'index'])->name('lecturer.guidance.activity');
                    Route::get('{studentActivity}', [LecturerActivityController::class, 'showStudentActivity'])->name('lecturer.guidance.activity.show');
                    Route::get('{studentActivity}/get-member', [LecturerActivityController::class, 'getMember'])->name('lecturer.guidance.activity.get-member');
                    Route::get('{studentActivity}/get-pembimbing', [LecturerActivityController::class, 'getPembimbing'])->name('lecturer.guidance.activity.get-pembimbing');
                    Route::get('{studentActivity}/get-penguji', [LecturerActivityController::class, 'getPenguji'])->name('lecturer.guidance.activity.get-penguji');
                });
            });
            // Profile
            Route::prefix('profile')->group(function () {
                Route::get('', [LecturerProfileController::class, 'index'])->name('lecturer.profile');
                Route::post('', [LecturerProfileController::class, 'update'])->name('lecturer.profile.update');
            });
            // Lecture
            Route::prefix('lecture')->group(function () {
                // Presences
                Route::prefix('presences')->middleware('can:read-lecturer-presences')->group(function () {
                    Route::get('get-courses', [LecturerPresenceController::class, 'getCourses'])->name('lecturer.lecture.presences.get-courses');
                    Route::get('get-college-classes', [LecturerPresenceController::class, 'getCollegeClasses'])->name('lecturer.lecture.presences.get-college-classes');
                    Route::get('get-class-participants', [LecturerPresenceController::class, 'getClassParticipants'])->name('lecturer.lecture.presences.get-class-participants');
                    Route::post('', [LecturerPresenceController::class, 'storeOrUpdate'])->middleware('can:update-lecturer-presences')->name('lecturer.lecture.presences.store-or-update');
                });

                // College Class
                Route::prefix('college-class')->middleware('can:read-lecturer-scheduling')->group(function () {
                    // Schedule
                    Route::prefix('schedule')->group(function () {
                        Route::get('', [LecturerCollegeClassController::class, 'index'])->name('lecturer.lecture.college-classes.schedule');
                        Route::get('get-courses', [LecturerCollegeClassController::class, 'getCourses'])->name('lecturer.lecture.college-classes.schedule.get-courses');
                        Route::get('get-college-classes', [LecturerCollegeClassController::class, 'getCollegeClasses'])->name('lecturer.lecture.college-classes.schedule.get-college-classes');
                        Route::get('{collegeClass}', [LecturerCollegeClassController::class, 'show'])->name('lecturer.lecture.college-class.show');
                    });

                    Route::prefix('{collegeClass}')->group(function () {
                        Route::get('class-participants', [LecturerCollegeClassController::class, 'classParticipant'])->name('lecturer.lecture.college-class.class-participants');
                        Route::prefix('class-schedules')->middleware('can:read-lecturer-college-class-schedules')->group(function () {
                            Route::get('', [ClassScheduleController::class, 'index'])->name('lecturer.lecture.college-class.class-schedule');
                            Route::post('', [ClassScheduleController::class, 'store'])->middleware('can:create-lecturer-college-class-schedules')->name('lecturer.lecture.college-class.class-schedule.store');
                            Route::post('generate-data', [ClassScheduleController::class, 'generateData'])->middleware('can:create-lecturer-college-class-schedules')->name('lecturer.lecture.college-class.class-schedule.generate-data');
                            Route::get('{classSchedule}', [ClassScheduleController::class, 'show'])->name('lecturer.lecture.college-class.class-schedule.show');
                            Route::post('{classSchedule}', [ClassScheduleController::class, 'update'])->middleware('can:update-lecturer-college-class-schedules')->name('lecturer.lecture.college-class.class-schedule.update');
                            Route::post('{classSchedule}/realization', [ClassScheduleController::class, 'fillRealization'])->middleware('can:update-lecturer-college-class-schedules')->name('lecturer.lecture.college-class.class-schedule.realization');
                            Route::delete('{classSchedule}', [ClassScheduleController::class, 'destroy'])->middleware('can:delete-lecturer-college-class-schedules')->name('lecturer.lecture.college-class.class-schedule.destroy');
                        });
                        Route::get('teaching-lecturers', [LecturerCollegeClassController::class, 'teachingLecturer'])->name('lecturer.lecture.college-class.teaching-lecturers');
                        Route::get('exam-schedules', [LecturerCollegeClassController::class, 'examSchedule'])->name('lecturer.lecture.college-class.exam-schedules');
                        Route::prefix('college-contracts')->group(function () {
                            Route::get('', [LecturerCollegeClassController::class, 'collegeContract'])->name('lecturer.lecture.college-class.college-contracts');
                            Route::post('', [LecturerCollegeClassController::class, 'createOrUpdateCollegeContract'])->middleware('can:update-lecturer-college-contracts')->name('lecturer.lecture.college-class.college-contracts.create-or-update');
                        });
                        Route::get('weekly-schedules', [LecturerCollegeClassController::class, 'weeklySchedule'])->name('lecturer.lecture.college-class.weekly-schedules');
                    });
                });

                // Student Grade
                Route::prefix('student-grades')->middleware('can:read-lecturer-student-grades')->group(function () {
                    Route::get('get-courses', [LecturerStudentGradeController::class, 'getCourses'])->name('lecturer.lecture.student-grades.get-courses');
                    Route::get('get-college-classes', [LecturerStudentGradeController::class, 'getCollegeClasses'])->name('lecturer.lecture.student-grades.get-college-classes');
                    Route::get('', [LecturerStudentGradeController::class, 'index'])->name('lecturer.lecture.student-grades');
                    Route::post('', [LecturerStudentGradeController::class, 'insertOrUpdate'])->middleware('can:update-lecturer-student-grades')->name('lecturer.lecture.student-grades.insert-or-update');
                    Route::post('lock', [LecturerStudentGradeController::class, 'lock'])->middleware('can:update-lecturer-student-grades')->name('lecturer.lecture.student-grades.lock');
                    Route::post('publish', [LecturerStudentGradeController::class, 'publish'])->middleware('can:update-lecturer-student-grades')->name('lecturer.lecture.student-grades.publish');
                });
            });

            // Guardianship
            Route::prefix('guardianships')->middleware('can:read-lecturer-guardianships')->group(function () {
                Route::get('', [GuardianshipController::class, 'index'])->name('lecturer.guardianships');
                Route::post('', [GuardianshipController::class, 'update'])->name('lecturer.guardianships.update');
            });

            // Announcement
            Route::prefix('announcements')->middleware('can:read-lecturer-announcements')->group(function () {
                Route::get('', [LecturerAnnouncementController::class, 'index'])->name('lecturer.annountcements');
            });
        });
    });
});
