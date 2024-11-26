<?php

use App\Http\Controllers\Api\Lecture\Administration\GuardianshipController;
use App\Http\Controllers\Api\Lecture\CollegeClass\StudentGradeController;
use App\Http\Controllers\Api\Lecture\JudicialParticipantController;
use App\Http\Controllers\Api\Lecture\PresenceController;
use App\Http\Controllers\Api\Log\UserAuthLogController;
use App\Http\Controllers\Api\Portal\CollegeStudentController;
use App\Http\Controllers\Api\Portal\EmployeeController;
use App\Http\Controllers\Api\Report\AttendancePercentageController;
use App\Http\Controllers\Api\Report\ReportCertificateController;
use App\Http\Controllers\Api\Report\ReportKhsController;
use App\Http\Controllers\Api\Student\FinalLevel\ThesisController;
use App\Http\Controllers\Report\ReportLectureController;
use App\Models\Guardianship;
use App\Models\JudicialParticipant;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::get('report/report-khs/print', [ReportKhsController::class, 'printKhs'])->name('report-print-khs');
    Route::get('report/student/total-students-status/print', [CollegeStudentController::class, 'printTotalStudentStatus']);
    Route::get('report/student/student-status/print', [CollegeStudentController::class, 'printStudentStatus']);
    Route::get('report/student/student-semester-status/print', [CollegeStudentController::class, 'printStudentSemester']);
    Route::get('report/student-transcript/print', [CollegeStudentController::class, 'printStudentTranscript']);
    Route::get('report/student/student-grade/print', [StudentGradeController::class, 'printStudentScores']);
    Route::get('report/student/student-gradepointaverage-rank/print', [StudentGradeController::class, 'printGpaRank']);
    Route::get('report/theses/print', [ThesisController::class, 'print']);
    Route::get('report/total-theses-students/print', [ThesisController::class, 'printTotalThesis']);
    Route::get('report/graduation-judicial/print', [JudicialParticipantController::class, 'print']);
    Route::get('report/employees/print', [EmployeeController::class, 'print']);
    Route::get('report/student/guardianships/print', [GuardianshipController::class, 'print']);
    Route::get('report/student/presence/presence-recap/print', [PresenceController::class, 'print']);
    Route::get('report/student/gpa/print', [StudentGradeController::class, 'printGPA']);
    Route::get('report/student/student-list/print', [CollegeStudentController::class, 'print']);
    Route::get('report/student/student-list/print', [CollegeStudentController::class, 'print']);
    Route::get('report/attendance-presentage/presentase/print', [AttendancePercentageController::class, 'print']);
    Route::get('report/lecture/schedule/print', [ReportLectureController::class, 'printSchedule']);
    Route::get('report/student/certificate/print', [ReportCertificateController::class, 'print']);
    Route::get('report/lecture/print-recap-schedule-lecture', [ReportLectureController::class, 'printRecapScheduleLecture']);



    Route::prefix('report')->middleware('auth:sanctum')->group(function() {

        Route::prefix('report-khs')->group(function () {
            Route::get('get-class-group/{academicYear}/{programStudy}', [ReportKhsController::class, 'getClassGroup']);
            Route::get('get-student-class-group/{classGroup}', [ReportKhsController::class, 'getStudents']);
        });
        Route::prefix('lecture')->group(function () {
            Route::get('get-college-class', [ReportLectureController::class, 'getCollegeClass']);
        });
        Route::prefix('certificate')->group(function () {
            Route::get('singel-search', [ReportCertificateController::class, 'singleSearch']);
        });
    });
});
