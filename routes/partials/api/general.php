<?php

use App\Http\Controllers\Api\General\GeneralController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('general')->group(function () {
            Route::get('academic-years', [GeneralController::class, 'academicYears'])->name('general.academic-year');
            Route::get('study-programs', [GeneralController::class, 'studyPrograms'])->name('general.study-program');
            Route::get('agencies', [GeneralController::class, 'getAgencies'])->name('general.agency');
            Route::get('religions', [GeneralController::class, 'getReligions'])->name('general.religion');
            Route::get('judicial-period', [GeneralController::class, 'getJudicialPeriods'])->name('general.judicial-period');
            Route::get('academic-periods', [GeneralController::class, 'academicPeriods'])->name('general.academic-period');
            Route::get('education-levels', [GeneralController::class, 'getEducationLevels'])->name('general.education-level');
            Route::get('majors', [GeneralController::class, 'getMajors'])->name('general.major');
            Route::get('regions', [GeneralController::class, 'getRegions'])->name('general.regions');
            Route::get('academic-activities', [GeneralController::class, 'getAcademicActivities'])->name('general.academic-activities');
            Route::get('users', [GeneralController::class, 'getUsers'])->name('general.users');
            Route::get('roles', [GeneralController::class, 'getRoles'])->name('general.roles');
            Route::get('employees', [GeneralController::class, 'getEmployees'])->name('general.employees');
            Route::get('employee-types', [GeneralController::class, 'getEmployeeTypes'])->name('general.employee-types');
            Route::get('employee-statuses', [GeneralController::class, 'getEmployeeStatuses'])->name('general.employee-statuses');
            Route::get('student-statuses', [GeneralController::class, 'studentStatuses'])->name('general.student-statuses');
            Route::get('lecture-system', [GeneralController::class, 'lectureSystem'])->name('general.lecture-system');
            Route::get('registration-types', [GeneralController::class, 'registrationType'])->name('general.registration-types');
            Route::get('registration-paths', [GeneralController::class, 'registrationPath'])->name('general.registration-paths');
            Route::get('curriculums', [GeneralController::class, 'curriculums'])->name('general.curriculums');
            Route::get('scientific-fields', [GeneralController::class, 'getScientificFields'])->name('general.scientific-fields');
            Route::get('universities', [GeneralController::class, 'getuniversities'])->name('general.universities');
            Route::get('employee-active-statuses', [GeneralController::class, 'getEmployeeActiveStatuses'])->name('general.employee-active-statuses');
            Route::get('countries', [GeneralController::class, 'getCountries'])->name('general.countries');
            Route::get('provinces', [GeneralController::class, 'getProvinces'])->name('general.provinces');
            Route::get('professions', [GeneralController::class, 'getProfessions'])->name('general.professions');
            Route::get('achievement-fields', [GeneralController::class, 'getAchievementFields'])->name('general.achievement-fields');
            Route::get('achievement-levels', [GeneralController::class, 'getAchievementLevels'])->name('general.achievement-levels');
            Route::get('achievement-groups', [GeneralController::class, 'getAchievementGroups'])->name('general.achievement-groups');
            Route::get('achievement-types', [GeneralController::class, 'getAchievementTypes'])->name('general.achievement-types');
            Route::get('scholarship-types', [GeneralController::class, 'getScholarshipTypes'])->name('general.scholarship-types');
            Route::get('rooms', [GeneralController::class, 'getRooms'])->name('general.rooms');
            Route::get('meeting-types', [GeneralController::class, 'getMeetingTypes'])->name('general.meeting-types');
            Route::get('college-classes', [GeneralController::class, 'getCollegeClasses'])->name('general.college-classes');
            Route::get('courses', [GeneralController::class, 'getCourses'])->name('general.courses');
            Route::get('active-score-scales', [GeneralController::class, 'getActiveScoreScales'])->name('general.active-score-scales');
            Route::get('class-schedules', [GeneralController::class, 'getClassSchedules'])->name('general.class-schedules');
            Route::get('weekly-schedules', [GeneralController::class, 'getWeeklySchedules'])->name('general.weekly-schedules');
            Route::post('get-data', [GeneralController::class, 'getData'])->name('general.get-data');
            Route::get('academic-calendars', [GeneralController::class, 'getAcademicCalendars'])->name('general.academic-calendars');
            Route::get('students', [GeneralController::class, 'getStudents'])->name('general.students');
            Route::get('days', [GeneralController::class, 'getDays'])->name('general.days');
            Route::get('time-slots', [GeneralController::class, 'getTimeSlots'])->name('general.time-slots');
            Route::get('ethnics', [GeneralController::class, 'getEthnics'])->name('general.ethnics');
            Route::get('incomes', [GeneralController::class, 'getIncomes'])->name('general.incomes');
            Route::get('disability', [GeneralController::class, 'getDisability'])->name('general.disability');
            Route::get('class-groups', [GeneralController::class, 'getClassGroups'])->name('general.class-group');
        });
    });
});
