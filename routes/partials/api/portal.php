<?php

use App\Http\Controllers\Api\Portal\AcademicActivityController;
use App\Http\Controllers\Api\Portal\AcademicCalendarController;
use App\Http\Controllers\Api\Portal\AcademicCalendarMonitoringController;
use App\Http\Controllers\Api\Portal\AnnouncementController;
use App\Http\Controllers\Api\Portal\EmployeeController;
use App\Http\Controllers\Api\Portal\CollegeStudentController;
use Illuminate\Support\Facades\Route;
Route::prefix('v1')->group(function () {
    Route::get('students/download-template-import-data', [CollegeStudentController::class, 'downloadTemplateImport']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('portal')->group(function () {
            Route::prefix('announcements')->middleware('can:read-announcements')->group(function () {
                Route::get('', [AnnouncementController::class, 'index'])->name('portal.announcements');
                Route::post('', [AnnouncementController::class, 'store'])->middleware('can:create-announcements')->name('portal.announcements.store');
                Route::get('{announcement}', [AnnouncementController::class, 'show'])->name('portal.announcements.show');
                Route::post('{announcement}', [AnnouncementController::class, 'update'])->middleware('can:update-announcements')->name('portal.announcements.update');
                Route::delete('{announcement}', [AnnouncementController::class, 'destroy'])->middleware('can:delete-announcements')->name('portal.announcements.destroy');
            });
            Route::prefix('employees')->middleware('can:read-employees')->group(function () {
                Route::get('', [EmployeeController::class, 'index'])->name('portal.employees');
                Route::post('', [EmployeeController::class, 'store'])->name('portal.employee.create')->middleware('can:create-employees');
                Route::post('set-rps', [EmployeeController::class, 'setRpsEmployee'])->name('portal.employee.set-rps')->middleware('can:update-employees');
                Route::prefix('{employee}')->group(function () {
                    Route::get('', [EmployeeController::class, 'show'])->name('portal.employee.show');
                    Route::delete('', [EmployeeController::class, 'destroy'])->name('portal.employee.destroy')->middleware('can:delete-employees');;
                    Route::post('', [EmployeeController::class, 'update'])->name('portal.employees.update')->middleware('can:update-employees');;
                    Route::get('teaching-lecturer', [EmployeeController::class, 'teachingLecturer'])->name('portal.employee.teaching-lecturer');
                    Route::get('activity', [EmployeeController::class, 'activity'])->name('portal.employee.activity');
                    Route::get('schedule-semester', [EmployeeController::class, 'scheduleSemester'])->name('portal.employee.schedule-semester');
                });
            });

            // College Student
            Route::prefix('college-students')->middleware('can:read-college-students')->group(function () {

                Route::get('', [CollegeStudentController::class, 'index'])->name('portal.college-students');
                Route::post('', [CollegeStudentController::class, 'store'])->name('portal.college-students.store')->middleware('can:create-college-students');
                Route::post('set-registration-paths', [CollegeStudentController::class, 'setRegistrationPath']);
                Route::post('set-registration-types', [CollegeStudentController::class, 'setRegistrationType']);
                Route::post('set-student-entrydate', [CollegeStudentController::class, 'setStudentEntryDate']);
                Route::post('set-lecture-system', [CollegeStudentController::class, 'setLectureSystem']);
                Route::post('set-class-group', [CollegeStudentController::class, 'setClassGroup']);
                Route::post('set-curriculum', [CollegeStudentController::class, 'setCurriculum']);
                Route::get('download-template-import-data', [CollegeStudentController::class, 'downloadTemplateImport']);
                Route::post('excel', [CollegeStudentController::class, 'importDataStudent']);
                Route::prefix('{student}')->group(function () {
                    Route::get('', [CollegeStudentController::class, 'show'])->name('portal.college-students.show');
                    Route::delete('', [CollegeStudentController::class, 'destroy'])->name('portal.college-students.delete')->middleware('can:delete-college-students');
                    Route::post('update', [CollegeStudentController::class, 'update'])->name('portal.college-students.update')->middleware('can:update-college-students');
                    Route::get('semester-status', [CollegeStudentController::class, 'semesterStatus']);
                    Route::get('score', [CollegeStudentController::class, 'studentScore']);
                    Route::get('student-transcript', [CollegeStudentController::class, 'studentTranscript']);
                    Route::get('student-achievement', [CollegeStudentController::class, 'studentAchievement']);
                    Route::get('score-conversion', [CollegeStudentController::class, 'studentScoreConversion']);
                    Route::get('student-krs', [CollegeStudentController::class, 'studentKrs']);
                    Route::get('student-khs', [CollegeStudentController::class, 'studentKhs']);
                    Route::get('student-curriculum', [CollegeStudentController::class, 'studentCurriculum']);
                });
            });

            Route::prefix('academic-calendars')->middleware('can:read-academic-calendars')->group(function () {
                Route::get('', [AcademicCalendarController::class, 'index'])->name('master.academic-calendars');
                Route::post('', [AcademicCalendarController::class, 'store'])->middleware('can:create-academic-calendars')->name('master.academic-calendars.store');
                Route::get('{academicCalendar}', [AcademicCalendarController::class, 'show'])->middleware('can:update-academic-calendars')->name('master.academic-calendars.show');
                Route::post('{academicCalendar}', [AcademicCalendarController::class, 'update'])->middleware('can:update-academic-calendars')->name('master.academic-calendars.update');
                Route::delete('{academicCalendar}', [AcademicCalendarController::class, 'destroy'])->middleware('can:delete-academic-calendars')->name('master.academic-calendars.destroy');
            });

            Route::prefix('academic-activities')->middleware('can:read-academic-activities')->group(function () {
                Route::get('', [AcademicActivityController::class, 'index'])->name('master.academic-activity');
                Route::post('', [AcademicActivityController::class, 'store'])->middleware('can:create-academic-activities')->name('master.academic-activity.store');
                Route::delete('{academicActivity}', [AcademicActivityController::class, 'destroy'])->middleware('can:delete-academic-activities')->name('master.academic-activity.destroy');
                Route::post('{academicActivity}', [AcademicActivityController::class, 'update'])->middleware('can:update-academic-activities')->name('master.academic-activity.update');
                Route::get('{academicActivity}', [AcademicActivityController::class, 'show'])->name('master.academic-activity.show');
            });

            Route::prefix('academic-calendar-monitorings')->middleware('can:read-academic-calendar-monitorings')->group(function () {
                Route::get('', [AcademicCalendarMonitoringController::class, 'index'])->name('portal.academic-calendar-monitorings');
            });
        });
    });
});
