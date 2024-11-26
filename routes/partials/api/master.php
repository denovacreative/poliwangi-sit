<?php

use App\Http\Controllers\Api\Master\AgencyController;
use App\Http\Controllers\Api\Master\CityController;
use App\Http\Controllers\Api\Master\CountryController;
use App\Http\Controllers\Api\Master\ClassGroupController;
use App\Http\Controllers\Api\Master\ContactPersonController;
use App\Http\Controllers\Api\Master\CourseGroupController;
use App\Http\Controllers\Api\Master\DisabilityController;
use App\Http\Controllers\Api\Master\EducationLevelController;
use App\Http\Controllers\Api\Master\EmployeeTypeController;
use App\Http\Controllers\Api\Master\EthnicController;
use App\Http\Controllers\Api\Master\LectureSystemController;
use App\Http\Controllers\Api\Master\MeetingTypeController;
use App\Http\Controllers\Api\Master\ProvinceController;
use App\Http\Controllers\Api\Master\ReligionController;
use App\Http\Controllers\Api\Master\RoomController;
use App\Http\Controllers\Api\Master\ScientificFieldController;
use App\Http\Controllers\Api\Master\StudentActivityCategoryController;
use App\Http\Controllers\Api\Master\StudentStatusController;
use App\Http\Controllers\Api\Master\SubDistrictController;
use App\Http\Controllers\Api\Master\TimeSlotController;
use App\Http\Controllers\Api\Master\UniversityController;
use App\Http\Controllers\Api\Master\MajorController;
use App\Http\Controllers\Api\Master\StudyProgramController;
use App\Http\Controllers\Api\Master\TransportationController;
use App\Http\Controllers\Api\Master\TypeOfStayController;
use App\Http\Controllers\Api\Master\CourseTypeController;
use App\Http\Controllers\Api\Master\UniversityProfileController;
use App\Http\Controllers\Api\Master\ProfessionController;
use App\Http\Controllers\Api\Master\IncomeController;
use App\Http\Controllers\Api\Master\EmployeeActiveStatusesController;
use App\Http\Controllers\Api\Master\EducationLevelSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('master')->group(function () {
            Route::prefix('universities')->middleware('can:read-universities')->group(function () {
                Route::get('', [UniversityController::class, 'index'])->name('master.university');
                Route::post('', [UniversityController::class, 'store'])->middleware('can:create-universities')->name('master.university.store');
                Route::delete('{university}', [UniversityController::class, 'destroy'])->middleware('can:delete-universities')->name('master.university.destroy');
                Route::post('{university}', [UniversityController::class, 'update'])->middleware('can:update-universities')->name('master.university.update');
                Route::get('{university}', [UniversityController::class, 'show'])->name('master.university.show');
            });

            Route::prefix('scientific-fields')->middleware('can:read-scientific-fields')->group(function () {
                Route::get('', [ScientificFieldController::class, 'index'])->name('master.scientific-fields');
                Route::post('', [ScientificFieldController::class, 'store'])->middleware('can:create-scientific-fields')->name('master.scientific-fields.store');
                Route::delete('{scientificField}', [ScientificFieldController::class, 'destroy'])->middleware('can:delete-scientific-fields')->name('master.scientific-fields.destroy');
                Route::post('{scientificField}', [ScientificFieldController::class, 'update'])->middleware('can:update-scientific-fields')->name('master.scientific-fields.update');
                Route::get('{scientificField}', [ScientificFieldController::class, 'show'])->name('master.scientific-fields.show');
            });

            Route::prefix('meeting-types')->middleware('can:read-meeting-types')->group(function () {
                Route::get('', [MeetingTypeController::class, 'index'])->name('master.meeting-types');
                Route::post('', [MeetingTypeController::class, 'store'])->middleware('can:create-meeting-types')->name('master.meeting-types.store');
                Route::delete('{meetingType}', [MeetingTypeController::class, 'destroy'])->middleware('can:delete-meeting-types')->name('master.meeting-types.destroy');
                Route::post('{meetingType}', [MeetingTypeController::class, 'update'])->middleware('can:update-meeting-types')->name('master.meeting-types.update');
                Route::get('{meetingType}', [MeetingTypeController::class, 'show'])->name('master.meeting-types.show');
            });

            Route::prefix('religions')->middleware('can:read-religions')->group(function () {
                Route::get('', [ReligionController::class, 'index'])->name('master.religions');
                Route::post('', [ReligionController::class, 'store'])->middleware('can:create-religions')->name('master.religions.store');
                Route::delete('{religion}', [ReligionController::class, 'destroy'])->middleware('can:delete-religions')->name('master.religions.destroy');
                Route::post('{religion}', [ReligionController::class, 'update'])->middleware('can:update-religions')->name('master.religions.update');
                Route::get('{religion}', [ReligionController::class, 'show'])->name('master.religions.show');
            });

            Route::prefix('ethnics')->middleware('can:read-ethnics')->group(function () {
                Route::get('', [EthnicController::class, 'index'])->name('master.ethnics');
                Route::post('', [EthnicController::class, 'store'])->middleware('can:create-ethnics')->name('master.ethnics.store');
                Route::delete('{ethnic}', [EthnicController::class, 'destroy'])->middleware('can:delete-ethnics')->name('master.ethnics.destroy');
                Route::post('{ethnic}', [EthnicController::class, 'update'])->middleware('can:update-ethnics')->name('master.ethnics.update');
                Route::get('{ethnic}', [EthnicController::class, 'show'])->name('master.ethnics.show');
            });

            Route::prefix('employee-types')->middleware('can:read-employee-types')->group(function () {
                Route::get('', [EmployeeTypeController::class, 'index'])->name('master.employee-types');
                Route::post('', [EmployeeTypeController::class, 'store'])->middleware('can:create-employee-types')->name('master.employee-types.store');
                Route::delete('{employeeType}', [EmployeeTypeController::class, 'destroy'])->middleware('can:delete-employee-types')->name('master.employee-types.destroy');
                Route::post('{employeeType}', [EmployeeTypeController::class, 'update'])->middleware('can:update-employee-types')->name('master.employee-types.update');
                Route::get('{employeeType}', [EmployeeTypeController::class, 'show'])->name('master.employee-types.show');
            });

            Route::prefix('countries')->middleware('can:read-countries')->group(function () {
                Route::get('', [CountryController::class, 'index'])->name('master.countries');
                Route::post('', [CountryController::class, 'store'])->middleware('can:create-countries')->name('master.countries.store');
                Route::delete('{country}', [CountryController::class, 'destroy'])->middleware('can:delete-countries')->name('master.countries.destroy');
                Route::post('{country}', [CountryController::class, 'update'])->middleware('can:update-countries')->name('master.countries.update');
                Route::get('{country}', [CountryController::class, 'show'])->name('master.countries.show');
            });

            Route::prefix('sub-districts')->middleware('can:read-sub-districts')->group(function () {
                Route::get('', [SubDistrictController::class, 'index'])->name('master.sub-districts');
                Route::post('', [SubDistrictController::class, 'store'])->name('master.sub-districts.store')->middleware('can:create-sub-districts');
                Route::post('{region}', [SubDistrictController::class, 'update'])->name('master.sub-distrcits.update')->middleware('can:update-sub-districts');
                Route::get('{region}', [SubDistrictController::class, 'show'])->name('master.sub-districts.show');
                Route::delete('{region}', [SubDistrictController::class, 'destroy'])->name('master.sub-districts.destroy')->middleware('can:delete-sub-districts');
            });

            Route::prefix('lecture-systems')->middleware('can:read-lecture-systems')->group(function () {
                Route::get('', [LectureSystemController::class, 'index'])->name('master.lecture-systems');
                Route::post('', [LectureSystemController::class, 'store'])->middleware('can:create-lecture-systems')->name('master.lecture-systems.store');
                Route::delete('{lectureSystem}', [LectureSystemController::class, 'destroy'])->middleware('can:delete-lecture-systems')->name('master.lecture-systems.destroy');
                Route::post('{lectureSystem}', [LectureSystemController::class, 'update'])->middleware('can:update-lecture-systems')->name('master.lecture-systems.update');
                Route::get('{lectureSystem}', [LectureSystemController::class, 'show'])->name('master.lecture-systems.show');
            });
            Route::prefix('time-slots')->middleware('can:read-time-slots')->group(function () {
                Route::get('', [TimeSlotController::class, 'index'])->name('master.time-slots');
                Route::post('', [TimeSlotController::class, 'store'])->middleware('can:create-time-slots')->name('master.time-slots.store');
                Route::delete('{timeSlot}', [TimeSlotController::class, 'destroy'])->middleware('can:delete-time-slots')->name('master.time-slots.destroy');
                Route::post('{timeSlot}', [TimeSlotController::class, 'update'])->middleware('can:update-time-slots')->name('master.time-slots.update');
                Route::get('{timeSlot}', [TimeSlotController::class, 'show'])->name('master.time-slots.show');
            });

            Route::prefix('majors')->middleware('can:read-majors')->group(function () {
                Route::get('', [MajorController::class, 'index'])->name('master.majors');
                Route::post('', [MajorController::class, 'store'])->name('master.majors.store')->middleware('can:create-majors');
                Route::delete('{major}', [MajorController::class, 'destroy'])->name('master.majors.delete')->middleware('can:delete-majors');
                Route::post('{major}', [MajorController::class, 'update'])->name('master.majors.update')->middleware('can:update-majors');
                Route::get('{major}', [MajorController::class, 'show'])->name('master.majors.show')->middleware('can:read-majors');
            });

            Route::prefix('rooms')->middleware('can:read-rooms')->group(function () {
                Route::get('', [RoomController::class, 'index'])->name('master.rooms');
                Route::post('', [RoomController::class, 'store'])->middleware('can:create-rooms')->name('master.rooms.store');
                Route::get('{room}', [RoomController::class, 'show'])->name('master.rooms.show');
                Route::post('{room}', [RoomController::class, 'update'])->middleware('can:update-rooms')->name('master.rooms.update');
                Route::delete('{room}', [RoomController::class, 'destroy'])->middleware('can:delete-rooms')->name('master.rooms.destroy');
            });
            Route::prefix('class-groups')->middleware('can:read-class-groups')->group(function () {
                Route::get('', [ClassGroupController::class, 'index'])->name('master.class-groups');
                Route::post('', [ClassGroupController::class, 'store'])->middleware('can:create-class-groups')->name('master.class-groups.store');
                Route::delete('{classGroup}', [ClassGroupController::class, 'destroy'])->middleware('can:delete-class-groups')->name('master.class-groups.destroy');
                Route::post('{classGroup}', [ClassGroupController::class, 'update'])->middleware('can:update-class-groups')->name('master.class-groups.update');
                Route::get('{classGroup}', [ClassGroupController::class, 'show'])->name('master.class-groups.show');
            });
            Route::prefix('student-statuses')->middleware('can:read-student-statuses')->group(function () {
                Route::get('', [StudentStatusController::class, 'index'])->name('master.student-statuses');
                Route::post('', [StudentStatusController::class, 'store'])->middleware('can:create-student-statuses')->name('master.student-statuses.store');
                Route::delete('{studentStatus}', [StudentStatusController::class, 'destroy'])->middleware('can:delete-student-statuses')->name('master.student-statuses.destroy');
                Route::post('{studentStatus}', [StudentStatusController::class, 'update'])->middleware('can:update-student-statuses')->name('master.student-statuses.update');
                Route::get('{studentStatus}', [StudentStatusController::class, 'show'])->name('master.student-statuses.show');
            });
            Route::prefix('contact-persons')->middleware('can:read-contact-persons')->group(function () {
                Route::get('', [ContactPersonController::class, 'index'])->name('master.contact-persons');
                Route::post('', [ContactPersonController::class, 'store'])->middleware('can:create-contact-persons')->name('master.contact-persons.store');
                Route::delete('{contactPerson}', [ContactPersonController::class, 'destroy'])->middleware('can:delete-contact-persons')->name('master.contact-persons.destroy');
                Route::post('{contactPerson}', [ContactPersonController::class, 'update'])->middleware('can:update-contact-persons')->name('master.contact-persons.update');
                Route::get('{contactPerson}', [ContactPersonController::class, 'show'])->name('master.contact-persons.show');
            });
            Route::prefix('study-programs')->middleware('can:read-study-programs')->group(function () {
                Route::get('', [StudyProgramController::class, 'index'])->name('master.study-programs');
                Route::post('', [StudyProgramController::class, 'store'])->middleware('can:create-study-programs')->name('master.study-programs.store');
                Route::delete('{studyProgram}', [StudyProgramController::class, 'destroy'])->middleware('can:delete-study-programs')->name('master.study-programs.destroy');
                Route::post('{studyProgram}', [StudyProgramController::class, 'update'])->middleware('can:update-study-programs')->name('master.study-programs.update');
                Route::get('{studyProgram}', [StudyProgramController::class, 'show'])->name('master.study-programs.show');
            });
            Route::prefix('agencies')->middleware('can:read-agencies')->group(function () {
                Route::get('', [AgencyController::class, 'index'])->name('master.agencies');
                Route::post('', [AgencyController::class, 'store'])->middleware('can:create-agencies')->name('master.agencies.store');
                Route::get('{agency}', [AgencyController::class, 'show'])->middleware('can:update-agencies')->name('master.agencies.show');
                Route::post('{agency}', [AgencyController::class, 'update'])->middleware('can:update-agencies')->name('master.agencies.update');
                Route::delete('{agency}', [AgencyController::class, 'destroy'])->middleware('can:delete-agencies')->name('master.agencies.destroy');
            });
            Route::prefix('education-levels')->middleware('can:read-education-levels')->group(function () {
                Route::get('', [EducationLevelController::class, 'index'])->name('master.education-levels');
                Route::post('', [EducationLevelController::class, 'store'])->middleware('can:create-education-levels')->name('master.education-levels.store');
                Route::get('{educationLevel}', [EducationLevelController::class, 'show'])->middleware('can:update-education-levels')->name('master.education-levels.show');
                Route::post('{educationLevel}', [EducationLevelController::class, 'update'])->middleware('can:update-education-levels')->name('master.education-levels.update');
                Route::delete('{educationLevel}', [EducationLevelController::class, 'destroy'])->middleware('can:delete-education-levels')->name('master.education-levels.destroy');
            });
            Route::prefix('course-groups')->middleware('can:read-course-groups')->group(function () {
                Route::get('', [CourseGroupController::class, 'index'])->name('master.course-groups');
                Route::post('', [CourseGroupController::class, 'store'])->middleware('can:create-course-groups')->name('master.course-groups.store');
                Route::get('{courseGroup}', [CourseGroupController::class, 'show'])->middleware('can:update-course-groups')->name('master.course-groups.show');
                Route::post('{courseGroup}', [CourseGroupController::class, 'update'])->middleware('can:update-course-groups')->name('master.course-groups.update');
                Route::delete('{courseGroup}', [CourseGroupController::class, 'destroy'])->middleware('can:delete-course-groups')->name('master.course-groups.destroy');
            });
            Route::prefix('student-activity-categories')->middleware('can:read-student-activity-categories')->group(function () {
                Route::get('', [StudentActivityCategoryController::class, 'index'])->name('master.student-activity-categories');
                Route::post('', [StudentActivityCategoryController::class, 'store'])->middleware('can:create-student-activity-categories')->name('master.student-activity-categories.store');
                Route::get('{studentActivityCategory}', [StudentActivityCategoryController::class, 'show'])->middleware('can:update-student-activity-categories')->name('master.student-activity-categories.show');
                Route::post('{studentActivityCategory}', [StudentActivityCategoryController::class, 'update'])->middleware('can:update-student-activity-categories')->name('master.student-activity-categories.update');
                Route::delete('{studentActivityCategory}', [StudentActivityCategoryController::class, 'destroy'])->middleware('can:delete-student-activity-categories')->name('master.student-activity-categories.destroy');
            });
            Route::prefix('disabilities')->middleware('can:read-disabilities')->group(function () {
                Route::get('', [DisabilityController::class, 'index'])->name('master.disabilities');
                Route::post('', [DisabilityController::class, 'store'])->middleware('can:create-disabilities')->name('master.disabilities.store');
                Route::get('{disability}', [DisabilityController::class, 'show'])->middleware('can:update-disabilities')->name('master.disabilities.show');
                Route::post('{disability}', [DisabilityController::class, 'update'])->middleware('can:update-disabilities')->name('master.disabilities.update');
                Route::delete('{disability}', [DisabilityController::class, 'destroy'])->middleware('can:delete-disabilities')->name('master.disabilities.destroy');
            });
            Route::prefix('type-of-stays')->middleware('can:read-type-of-stays')->group(function () {
                Route::get('', [TypeOfStayController::class, 'index'])->name('master.type-of-stays');
                Route::post('', [TypeOfStayController::class, 'store'])->middleware('can:create-type-of-stays')->name('master.type-of-stays.store');
                Route::get('{typeOfStay}', [TypeOfStayController::class, 'show'])->middleware('can:update-type-of-stays')->name('master.type-of-stays.show');
                Route::post('{typeOfStay}', [TypeOfStayController::class, 'update'])->middleware('can:update-type-of-stays')->name('master.type-of-stays.update');
                Route::delete('{typeOfStay}', [TypeOfStayController::class, 'destroy'])->middleware('can:delete-type-of-stays')->name('master.type-of-stays.destroy');
            });
            Route::prefix('transportations')->middleware('can:read-transportations')->group(function () {
                Route::get('', [TransportationController::class, 'index'])->name('master.transportations');
                Route::post('', [TransportationController::class, 'store'])->middleware('can:create-transportations')->name('master.transportations.store');
                Route::get('{transportation}', [TransportationController::class, 'show'])->middleware('can:update-transportations')->name('master.transportations.show');
                Route::post('{transportation}', [TransportationController::class, 'update'])->middleware('can:update-transportations')->name('master.transportations.update');
                Route::delete('{transportation}', [TransportationController::class, 'destroy'])->middleware('can:delete-transportations')->name('master.transportations.destroy');
            });
            Route::prefix('provinces')->middleware('can:read-provinces')->group(function () {
                Route::get('', [ProvinceController::class, 'index'])->name('master.provinces');
                Route::post('', [ProvinceController::class, 'store'])->middleware('can:create-provinces')->name('master.provinces.store');
                Route::get('{region}', [ProvinceController::class, 'show'])->middleware('can:update-provinces')->name('master.provinces.show');
                Route::post('{region}', [ProvinceController::class, 'update'])->middleware('can:update-provinces')->name('master.provinces.update');
                Route::delete('{region}', [ProvinceController::class, 'destroy'])->middleware('can:delete-provinces')->name('master.provinces.destroy');
            });
            Route::prefix('cities')->middleware('can:read-cities')->group(function () {
                Route::get('', [CityController::class, 'index'])->name('master.cities');
                Route::post('', [CityController::class, 'store'])->middleware('can:create-cities')->name('master.cities.store');
                Route::get('{region}', [CityController::class, 'show'])->middleware('can:update-cities')->name('master.cities.show');
                Route::post('{region}', [CityController::class, 'update'])->middleware('can:update-cities')->name('master.cities.update');
                Route::delete('{region}', [CityController::class, 'destroy'])->middleware('can:delete-cities')->name('master.cities.destroy');
            });

            // Course Type
            Route::prefix('course-types')->middleware('can:read-course-types')->group(function () {
                Route::get('', [CourseTypeController::class, 'index'])->name('master.course-types');
                Route::post('', [CourseTypeController::class, 'store'])->name('master.course-types.store')->middleware('can:create-course-types');
                Route::delete('{courseType}', [CourseTypeController::class, 'destroy'])->name('master.course-types.delete')->middleware('can:delete-course-types');
                Route::post('{courseType}', [CourseTypeController::class, 'update'])->name('master.course-types.update')->middleware('can:update-course-types');
                Route::get('{courseType}', [CourseTypeController::class, 'show'])->name('master.course-types.show')->middleware('can:read-course-types');
            });

            // University Profile
            Route::prefix('university-profiles')->middleware('can:read-university-profiles')->group(function () {
                Route::get('', [UniversityProfileController::class, 'index'])->name('master.university-profiles');
                Route::post('{universityProfile}', [UniversityProfileController::class, 'update'])->name('master.university-profiles.update')->middleware('can:update-university-profiles');
            });

            // Professions
            Route::prefix('professions')->middleware('can:read-professions')->group(function () {
                Route::get('', [ProfessionController::class, 'index'])->name('master.professions');
                Route::post('', [ProfessionController::class, 'store'])->name('master.professions.store')->middleware('can:create-professions');
                Route::delete('{profession}', [ProfessionController::class, 'destroy'])->name('master.professions.delete')->middleware('can:delete-professions');
                Route::post('{profession}', [ProfessionController::class, 'update'])->name('master.professions.update')->middleware('can:update-professions');
                Route::get('{profession}', [ProfessionController::class, 'show'])->name('master.professions.show');
            });

            // lincome
            Route::prefix('incomes')->middleware('can:read-incomes')->group(function () {
                Route::get('', [IncomeController::class, 'index'])->name('master.incomes');
                Route::post('', [IncomeController::class, 'store'])->name('master.incomes.store')->middleware('can:create-incomes');
                Route::delete('{income}', [IncomeController::class, 'destroy'])->name('master.incomes.delete')->middleware('can:delete-incomes');
                Route::post('{income}', [IncomeController::class, 'update'])->name('master.incomes.update')->middleware('can:update-incomes');
                Route::get('{income}', [IncomeController::class, 'show'])->name('master.incomes.show');
            });

            // Employee Active Statuses
            Route::prefix('employee/active-statuses')->middleware('can:read-employee-active-statuses')->group(function () {
                Route::get('', [EmployeeActiveStatusesController::class, 'index'])->name('master.employee-active-statuses');
            });

            // Education Level Settings
            Route::prefix('education-level-settings')->middleware('can:read-education-level-settings')->group(function () {
                Route::get('', [EducationLevelSettingController::class, 'index'])->name('master.education-level-settings');
                Route::post('', [EducationLevelSettingController::class, 'store'])->name('master.education-level-settings.store')->middleware('can:create-education-level-settings');
                Route::delete('{educationLevelSetting}', [EducationLevelSettingController::class, 'destroy'])->name('master.education-level-settings.destroy')->middleware('can:delete-education-level-settings');
                Route::post('{educationLevelSetting}', [EducationLevelSettingController::class, 'update'])->name('master.education-level-settings.update')->middleware('can:update-education-level-settings');
                Route::get('{educationLevelSetting}', [EducationLevelSettingController::class, 'show'])->name('master.education-level-settings.show');
            });
        });
    });
});
