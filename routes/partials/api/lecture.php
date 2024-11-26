<?php

use App\Http\Controllers\Api\Lecture\ActivityScoreConversionController;
use App\Http\Controllers\Api\Lecture\Administration\GuardianshipController;
use App\Http\Controllers\Api\Lecture\Administration\HerRegistrationController;
use App\Http\Controllers\Api\Lecture\Administration\StatusSemesterController;
use App\Http\Controllers\Api\Lecture\ClassScheduleController;
use App\Http\Controllers\Api\Lecture\CollegeClass\ClassAttendanceController;
use App\Http\Controllers\Api\Lecture\CollegeClass\ClassParticipantController;
use App\Http\Controllers\Api\Lecture\CollegeClass\ClassScheduleController as LectureClassScheduleController;
use App\Http\Controllers\Api\Lecture\CollegeClass\ExamScheduleController;
use App\Http\Controllers\Api\Lecture\CollegeClass\CollegeContractController;
use App\Http\Controllers\Api\Lecture\GraduationPredicateController;
use App\Http\Controllers\Api\Lecture\JudicialPeriodController;
use App\Http\Controllers\Api\Lecture\CollegeClass\ScheduleController;
use App\Http\Controllers\Api\Lecture\CollegeClass\TeachingLecturerController;
use App\Http\Controllers\Api\Lecture\CollegeClass\WeeklyScheduleController;
use App\Http\Controllers\Api\Lecture\CourseController;
use App\Http\Controllers\Api\Lecture\CourseCurriculumController;
use App\Http\Controllers\Api\Lecture\CurriculumController;
use App\Http\Controllers\Api\Lecture\JudicialRequirementController;
use App\Http\Controllers\Api\Lecture\ScoreScaleController;
use App\Http\Controllers\Api\Lecture\PresenceController;
use App\Http\Controllers\Api\Lecture\StudentGradeController;
use App\Http\Controllers\Api\Lecture\CollegeClass\StudentGradeController as ClassStudentGradeController;
use App\Http\Controllers\Api\Lecture\Graduation\GraduationController;
use App\Http\Controllers\Api\Lecture\JudicialParticipantController;
use App\Http\Controllers\Api\Lecture\StudentActivityController;
use App\Http\Controllers\Api\Lecture\ThesesController;
use App\Http\Controllers\Api\Lecture\ThesisRequirementController;
use App\Http\Controllers\Api\Lecture\TranscriptController;
use App\Http\Controllers\Api\Student\FinalLevel\ThesisController;
use App\Http\Controllers\ClassRecapController;
use App\Models\JudicialParticipant;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('lecture/activities/download-template-import-data', [StudentActivityController::class, 'downloadTemplateImport'])->name('lecture.activities.download-template-data');
    Route::get('theses/download-template-import-data', [ThesesController::class, 'downloadTemplateImport']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('lecture')->group(function () {
            Route::prefix('judicial-periods')->middleware('can:read-judicial-periods')->group(function () {
                Route::get('', [JudicialPeriodController::class, 'index'])->name('lecture.judicial-period');
                Route::post('', [JudicialPeriodController::class, 'store'])->middleware('can:create-judicial-periods')->name('lecture.judicial-period.store');
                Route::delete('{judicialPeriod}', [JudicialPeriodController::class, 'destroy'])->middleware('can:delete-judicial-periods')->name('lecture.judicial-period.destroy');
                Route::post('{judicialPeriod}', [JudicialPeriodController::class, 'update'])->middleware('can:update-judicial-periods')->name('lecture.judicial-period.update');
                Route::get('{judicialPeriod}', [JudicialPeriodController::class, 'show'])->name('lecture.judicial-period.show');
                Route::get('{judicialPeriod}/status', [JudicialPeriodController::class, 'updateStatus'])->name('lecture.judicial-period.update-status');
            });
            Route::prefix('judicial-requirements')->middleware('can:read-judicial-requirements')->group(function () {
                Route::get('', [JudicialRequirementController::class, 'index'])->name('lecture.judicial-requirement');
                Route::post('', [JudicialRequirementController::class, 'store'])->middleware('can:create-judicial-requirements')->name('lecture.judicial-requirement.store');
                Route::delete('{judicialRequirement}', [JudicialRequirementController::class, 'destroy'])->middleware('can:delete-judicial-requirements')->name('lecture.judicial-requirement.destroy');
                Route::post('{judicialRequirement}', [JudicialRequirementController::class, 'update'])->middleware('can:update-judicial-requirements')->name('lecture.judicial-requirement.update');
                Route::get('{judicialRequirement}', [JudicialRequirementController::class, 'show'])->name('lecture.judicial-requirement.show');
            });

            Route::prefix('judicial-participants')->middleware('can:read-judicials')->group(function() {
                Route::get('', [JudicialParticipantController::class, 'index'])->middleware('can:read-judicials')->name('judicial-participants');
                Route::post('', [JudicialParticipantController::class, 'store'])->middleware('can:create-judicials')->name('judicial-participants.store');
                Route::get('search-student', [JudicialParticipantController::class, 'searchStudent'])->name('judicial-participants.search-student');
                Route::get('get-requirements', [JudicialParticipantController::class, 'getRequirements'])->name('judicial-participants.get-requirements');
                Route::delete('{judicialParticipant}', [JudicialParticipantController::class, 'destroy'])->middleware('can:delete-judicials')->name('judicial-participants.delete');
                Route::get('{judicialParticipantId}', [JudicialParticipantController::class, 'show'])->name('judicial-participants.show');
                Route::post('{judicialParticipantId}/update', [JudicialParticipantController::class, 'update'])->middleware('can:update-judicials')->name('judicial-participants.update');
            });

            Route::prefix('college-class')->group(function () {
                // Route College Class > Schedule
                Route::prefix('schedule')->middleware('can:read-scheduling')->group(function () {
                    Route::get('', [ScheduleController::class, 'index'])->name('lecture.college-class.schedule');
                    Route::post('', [ScheduleController::class, 'store'])->name('lecture.college-class.schedule.store')->middleware('can:create-scheduling');
                    Route::delete('{collegeClass}', [ScheduleController::class, 'destroy'])->name('lecture.college-class.schedule.destroy')->middleware('can:delete-scheduling');
                    Route::post('{collegeClass}', [ScheduleController::class, 'update'])->name('lecture.college-class.schedule.update')->middleware('can:update-scheduling');
                    Route::get('{collegeClass}', [ScheduleController::class, 'show'])->name('lecture.college-class.schedule.show');
                });

                Route::prefix('/recaps')->middleware('can:read-class-recap')->group(function (){
                    Route::get('',[ClassRecapController::class, 'index'])->name('lecture.collage-class.recap');
                });

                Route::prefix('{collegeClass}')->group(function () {

                    Route::prefix('class-participants')->middleware('can:read-class-participants')->group(function () {
                        Route::get('', [ClassParticipantController::class, 'index'])->name('lecture.college-class.class-participants');
                        Route::get('eligibility-check', [ClassParticipantController::class, 'checkLecturerEligibilityToCreate'])->name('lecture.college-class.class-participants');
                        Route::post('', [ClassParticipantController::class, 'store'])->middleware('can:create-class-participants')->name('lecture.college-class.class-participants.insert');
                        Route::get('students-list', [ClassParticipantController::class, 'getClassParticipantStudentList'])->middleware('can:create-class-participants')->name('lecture.college-class.class-participants.get-student-class-participants-list');
                        Route::get('single-search', [ClassParticipantController::class, 'getSingleStudentSearchResult'])->middleware('can:create-class-participants')->name('lecture.college-class.class-participants.single-search');
                        Route::delete('{classParticipant}', [ClassParticipantController::class, 'destroy'])->middleware('can:delete-class-participants')->name('lecture.college-class.class-participants.set-coordinator');
                        Route::post('{classParticipant}/set-coordinator', [ClassParticipantController::class, 'setCoordinator'])->middleware('can:update-class-participants')->name('lecture.college-class.class-participants.set-coordinator');
                    });

                    Route::prefix('class-schedules')->middleware('can:read-college-class-schedules')->group(function () {
                        Route::get('', [LectureClassScheduleController::class, 'index'])->name('lecture.college-class.class-schedule');
                        Route::post('', [LectureClassScheduleController::class, 'store'])->middleware('can:create-college-class-schedules')->name('lecture.college-class.class-schedule.store');
                        Route::post('generate-data', [LectureClassScheduleController::class, 'generateData'])->middleware('can:create-college-class-schedules')->name('lecture.college-class.class-schedule.generate-data');
                        Route::get('{classSchedule}', [LectureClassScheduleController::class, 'show'])->name('lecture.college-class.class-schedule.show');
                        Route::post('{classSchedule}/realization', [LectureClassScheduleController::class, 'fillRealization'])->middleware('can:update-college-class-schedules')->name('lecture.college-class.class-schedule.realization');
                        Route::post('{classSchedule}', [LectureClassScheduleController::class, 'update'])->middleware('can:update-college-class-schedules')->name('lecture.college-class.class-schedule.update');
                        Route::delete('{classSchedule}', [LectureClassScheduleController::class, 'destroy'])->middleware('can:delete-college-class-schedules')->name('lecture.college-class.class-schedule.destroy');
                    });

                    // Route College Class > Class Attendance
                    Route::prefix('class-attendances')->middleware('can:read-class-attendances')->group(function () {
                        Route::get('', [ClassAttendanceController::class, 'index'])->name('lecture.college-class.class-attendances');
                        Route::post('/presence', [ClassAttendanceController::class, 'updatePresence'])->middleware('can:update-class-attendances')->name('lecture.college-class.class-attendances.presence');
                    });


                    // Route College Class > Teaching Lecturer
                    Route::prefix('teaching-lecturers')->middleware('can:read-teaching-lecturers')->group(function () {
                        Route::get('', [TeachingLecturerController::class, 'index'])->name('lecture.college-class.teaching-lecturers');
                        Route::get('calculate-credit', [TeachingLecturerController::class, 'calculateCredit'])->name('lecture.college-class.teaching-lecturers.calculate-credit');
                        Route::post('', [TeachingLecturerController::class, 'store'])->name('lecture.college-class.teaching-lecturers.store');
                        Route::get('{teachingLecturer}', [TeachingLecturerController::class, 'show'])->name('lecture.college-class.teaching-lecturers');
                        Route::post('{teachingLecturer}', [TeachingLecturerController::class, 'store'])->name('lecture.college-class.teaching-lecturers.store');
                        Route::post('{teachingLecturer}', [TeachingLecturerController::class, 'update'])->name('lecture.college-class.teaching-lecturers.update');
                        Route::delete('{teachingLecturer}', [TeachingLecturerController::class, 'destroy'])->name('lecture.college-class.teaching-lecturers.destroy');
                        Route::get('{teachingLecturer}/score', [TeachingLecturerController::class, 'updateScore'])->name('lecture.college-class.teaching-lecturers.update-score');
                    });
                    // Route College Class > Exam Schedule
                    Route::prefix('exam-schedules')->middleware('can:read-exam-schedules')->group(function () {
                        Route::get('', [ExamScheduleController::class, 'index'])->name('lecture.college-class.exam-schedules');
                        Route::post('', [ExamScheduleController::class, 'store'])->name('lecture.college-class.exam-schedules.store');
                        Route::get('{examSchedule}', [ExamScheduleController::class, 'show'])->name('lecture.college-class.exam-schedules');
                        Route::post('{examSchedule}', [ExamScheduleController::class, 'store'])->name('lecture.college-class.exam-schedules.store');
                        Route::post('{examSchedule}', [ExamScheduleController::class, 'update'])->name('lecture.college-class.exam-schedules.update');
                        Route::delete('{examSchedule}', [ExamScheduleController::class, 'destroy'])->name('lecture.college-class.exam-schedules.destroy');
                    });

                    // Route College Class > College Contract
                    Route::prefix('college-contracts')->middleware('can:read-college-contracts')->group(function () {
                        Route::get('', [CollegeContractController::class, 'index'])->name('lecture.college-class.college-contracts');
                        Route::post('', [CollegeContractController::class, 'createOrUpdate'])->name('lecture.college-class.college-contracts.create-or-update')->middleware('can:update-college-contracts');
                    });

                    // Route College Class > Weekly Schedule
                    Route::prefix('weekly-schedules')->middleware('can:read-weekly-schedules')->group(function () {
                        Route::get('', [WeeklyScheduleController::class, 'index'])->name('lecture.college-class.weekly-schedules');
                        Route::post('', [WeeklyScheduleController::class, 'store'])->middleware('can:create-weekly-schedules')->name('lecture.college-class.weekly-schedules.store');
                        Route::get('{weeklySchedule}', [WeeklyScheduleController::class, 'show'])->middleware('can:update-weekly-schedules')->name('lecture.college-class.weekly-schedules.show');
                        Route::post('{weeklySchedule}', [WeeklyScheduleController::class, 'update'])->middleware('can:update-weekly-schedules')->name('lecture.college-class.weekly-schedules.update');
                        Route::delete('{weeklySchedule}', [WeeklyScheduleController::class, 'destroy'])->middleware('can:delete-weekly-schedules')->name('lecture.college-class.weekly-schedules.destroy');
                    });
                    // Route College Class > Student Grades
                    Route::prefix('student-grades')->group(function () {
                        Route::get('', [ClassStudentGradeController::class, 'index'])->name('lecture.college-class.student-grades');
                        Route::post('', [ClassStudentGradeController::class, 'update'])->name('lecture.college-class.student-grades.update')->middleware('can:update-student-grades');
                        Route::get('lock', [ClassStudentGradeController::class, 'lock'])->name('lecture.college-class.student-grades.lock')->middleware('can:update-student-grades');
                        Route::get('published', [ClassStudentGradeController::class, 'publishScore'])->name('lecture.college-class.student-grades.publish')->middleware('can:update-student-grades');
                    });
                });
            });

            Route::prefix('presences')->middleware('can:read-presences')->group(function () {
                Route::get('get-courses', [PresenceController::class, 'getCourse'])->name('lecture.college-class.presences.get-courses');
                Route::get('get-college-classes', [PresenceController::class, 'getCollegeClass'])->name('lecture.college-class.presences.get-college-classes');
                Route::get('get-class-participant', [PresenceController::class, 'getClassParticipant'])->name('lecture.college-class.presences.get-class-participant');
                Route::post('', [PresenceController::class, 'storeOrUpdate'])->name('lecture.college-class.presences.store-or-update');
            });

            Route::prefix('administration')->group(function () {
                Route::prefix('her-registration')->middleware('can:read-her-registration')->group(function () {
                    Route::get('', [HerRegistrationController::class, 'index']);
                    Route::post('{heregistration}/validate', [HerRegistrationController::class, 'validateData']);
                });
                Route::prefix('guardianships')->middleware('can:read-guardianships')->group(function () {
                    Route::get('', [GuardianshipController::class, 'index'])->name('lecture.administration.guardianships');
                    Route::get('{employee}/get-student-list', [GuardianshipController::class, 'getStudentList'])->name('lecture.administration.guardianships.getStudentList');
                    Route::get('{employee}', [GuardianshipController::class, 'show'])->name('lecture.administration.guardianships.show');
                    Route::post('{employee}', [GuardianshipController::class, 'store'])->middleware('can:create-guardianships')->name('lecture.administration.guardianships.store');
                    Route::post('{employee}/delete', [GuardianshipController::class, 'destroy'])->middleware('can:delete-guardianships')->name('lecture.administration.guardianships.destroy');
                });

                Route::prefix('status-semester')->group(function () {
                    Route::get('', [StatusSemesterController::class, 'getStatusSemester'])->middleware('can:read-dashboard')->name('lecture.administration.status-semester');
                    Route::post('generate', [StatusSemesterController::class, 'generate'])->name('lecture.administration.status-semester.generate');
                    Route::post('recalculate', [StatusSemesterController::class, 'recalculate'])->name('lecture.administration.status-semester.recalculate');
                    Route::post('generate-student', [StatusSemesterController::class, 'generateStudent'])->name('lecture.administration.status-semester.generate-student');
                    Route::get('generate-credit-grade/{student}/{academic_period}', [StatusSemesterController::class, 'generateValue'])->name('lecture.administration.status-semester.generate-value');
                    Route::get('detail-status-semester-student/{study_program}/{academic_period}/{status_student}', [StatusSemesterController::class, 'getDetailStudent'])->name('lecture.administration.status-semester.detail-status-semester-student');
                    Route::get('detail-status-semester-student/{student_college_activity}/get-data', [StatusSemesterController::class, 'showDataDetailStudent'])->name('lecture.administration.status-semester.detail-status-semester-student.getData');
                    Route::post('update-detail-student', [StatusSemesterController::class, 'updateDetailAkm'])->name('lecture.administration.status-semester.updateDetail');
                    Route::get('single-search', [StatusSemesterController::class, 'singleSearch'])->name('lecture.administration.status-semester.singleSearchStudent');
                    Route::delete('{student_college_activity}/delete-detail', [StatusSemesterController::class, 'deleteDetail'])->name('lecture.administration.status-semester.deleteDetail');
                });
            });

            Route::prefix('transcript')->middleware('can:read-transcripts')->group(function () {
                Route::get('', [TranscriptController::class, 'index'])->name('lecture.transcript');
                Route::get('{study_program}/{academic_year}', [TranscriptController::class, 'update'])->name('lecture.transcript.update')->middleware('can:update-transcripts');
            });

            Route::prefix('score-scales')->middleware('can:read-score-scales')->group(function () {
                Route::get('', [ScoreScaleController::class, 'index'])->name('lecture.score-scales');
                Route::post('', [ScoreScaleController::class, 'store'])->middleware('can:create-score-scales')->name('lecture.score-scales.store');
                Route::get('{scoreScale}', [ScoreScaleController::class, 'show'])->middleware('can:update-score-scales')->name('lecture.score-scales.show');
                Route::post('{scoreScale}', [ScoreScaleController::class, 'update'])->middleware('can:update-score-scales')->name('lecture.score-scales.update');
                Route::delete('{scoreScale}', [ScoreScaleController::class, 'destroy'])->middleware('can:delete-score-scales')->name('lecture.score-scales.destroy');
            });

            Route::prefix('courses')->middleware('can:read-courses')->group(function () {
                Route::get('', [CourseController::class, 'index'])->name('lecture.courses');
                Route::post('', [CourseController::class, 'store'])->middleware('can:create-courses')->name('lecture.courses.store');
                Route::post('{course}', [CourseController::class, 'update'])->name('lecture.courses.update')->middleware('can:update-courses');
                Route::get('{course}', [CourseController::class, 'show'])->name('lecture.courses.show');
                Route::delete('{course}', [CourseController::class, 'destroy'])->middleware('can:delete-courses')->name('lecture.courses.delete');
            });

            Route::prefix('graduation-predicates')->middleware('can:read-graduation-predicates')->group(function () {
                Route::get('', [GraduationPredicateController::class, 'index'])->name('lecture.graduation-predicates');
                Route::post('', [GraduationPredicateController::class, 'store'])->middleware('can:create-graduation-predicates')->name('lecture.graduation-predicates.store');
                Route::get('{graduationPredicate}', [GraduationPredicateController::class, 'show'])->middleware('can:update-graduation-predicates')->name('lecture.graduation-predicates.show');
                Route::post('{graduationPredicate}', [GraduationPredicateController::class, 'update'])->middleware('can:update-graduation-predicates')->name('lecture.graduation-predicates.update');
                Route::delete('{graduationPredicate}', [GraduationPredicateController::class, 'destroy'])->middleware('can:delete-graduation-predicates')->name('lecture.graduation-predicates.destroy');
            });

            Route::prefix('class-schedules')->middleware('can:read-class-schedules')->group(function () {
                Route::get('', [ClassScheduleController::class, 'index'])->name('lecture.class-schedules');
                Route::get('{classSchedule}/presence', [ClassScheduleController::class, 'presence'])->name('lecture.class-schedules.presence');
                Route::post('{classSchedule}/presence', [ClassScheduleController::class, 'updatePresence'])->middleware('can:update-class-schedule-presences')->name('lecture.class-schedules.updatePresence');
                Route::delete('{classSchedule}', [ClassScheduleController::class, 'destroy'])->middleware('can:delete-class-schedules')->name('lecture.class-schedules.destroy');
            });

            Route::prefix('curriculums')->middleware('can:read-curriculums')->group(function () {
                Route::get('', [CurriculumController::class, 'index'])->name('lecture.curriculums');
                Route::post('', [CurriculumController::class, 'store'])->middleware('can:create-curriculums')->name('lecture.curriculums.store');
                Route::get('{curriculum}/get-credit-count', [CurriculumController::class, 'getCreditCount'])->name('lecture.curriculums.get-credit-count');
                Route::get('{curriculum}', [CurriculumController::class, 'show'])->name('lecture.curriculums.show');
                Route::post('{curriculum}', [CurriculumController::class, 'update'])->middleware('can:update-curriculums')->name('lecture.curriculums.update');
                Route::delete('{curriculum}', [CurriculumController::class, 'destroy'])->middleware('can:delete-curriculums')->name('lecture.curriculums.delete');
                // Route::post('', [ClassScheduleController::class, 'store'])->middleware('can:create-class-schedules')->name('lecture.class-schedules.store');
                // Route::get('{classSchedule}', [ClassScheduleController::class, 'show'])->middleware('can:update-class-schedules')->name('lecture.class-schedules.show');
                // Route::post('{classSchedule}', [ClassScheduleController::class, 'update'])->middleware('can:update-class-schedules')->name('lecture.class-schedules.update');
                // Route::delete('{classSchedule}', [ClassScheduleController::class, 'destroy'])->middleware('can:delete-class-schedules')->name('lecture.class-schedules.destroy');
            });

            Route::prefix('course-curriculums')->middleware('can:read-course-curiculums')->group(function () {
                Route::get('', [CourseCurriculumController::class, 'index'])->name('lecture.course-curiculums');
                Route::post('', [CourseCurriculumController::class, 'store'])->middleware('can:create-course-curiculums')->name('lecture.course-curriculums.store');
                Route::get('{courseCurriculum}', [CourseCurriculumController::class, 'show'])->name('lecture.course-curriculums.show');
                Route::post('{courseCurriculum}', [CourseCurriculumController::class, 'update'])->middleware('can:update-course-curiculums')->name('lecture.course-curriculums.update');
                Route::delete('{courseCurriculum}', [CourseCurriculumController::class, 'destroy'])->middleware('can:delete-course-curiculums')->name('lecture.course-curriculums.delete');
            });

            Route::prefix('theses')->middleware('can:read-theses')->group(function () {
                Route::get('', [ThesesController::class, 'index'])->name('lecture.theses');
                Route::post('excel', [ThesesController::class, 'importDataTheses']);
                Route::post('', [ThesesController::class, 'store'])->middleware('can:create-theses')->name('lecture.theses.store');
                Route::get('{thesis}', [ThesesController::class, 'show'])->name('lecture.theses.show');
                Route::post('{thesis}', [ThesesController::class, 'update'])->middleware('can:update-theses')->name('lecture.theses.update');
                Route::delete('{thesis}', [ThesesController::class, 'destroy'])->middleware('can:delete-theses')->name('lecture.theses.delete');
            });
            // Route::prefix('thesis-requirements')->middleware('can:read-thesis-requirements')->group(function () {
            //     Route::get('', [ThesisRequirementController::class, 'index'])->name('lecture.thesis-requirements');
            //     Route::post('', [ThesisRequirementController::class, 'store'])->middleware('can:create-thesis-requirements')->name('lecture.thesis-requirements.store');
            //     Route::get('{thesisRequirement}', [ThesisRequirementController::class, 'show'])->name('lecture.thesis-requirements.show');
            //     Route::post('{thesisRequirement}', [ThesisRequirementController::class, 'update'])->middleware('can:update-thesis-requirements')->name('lecture.thesis-requirements.update');
            //     Route::delete('{thesisRequirement}', [ThesisRequirementController::class, 'destroy'])->middleware('can:delete-thesis-requirements')->name('lecture.thesis-requirements.delete');
            // });

            Route::prefix('student-grades')->middleware('can:read-student-grades')->group(function () {
                Route::get('', [StudentGradeController::class, 'index'])->name('lecture.student-grades');
                Route::get('get-courses', [StudentGradeController::class, 'getCourses'])->name('lecture.student-grades.get-courses');
                Route::get('get-college-classes', [StudentGradeController::class, 'getCollegeClasses'])->name('lecture.student-grades.get-college-classes');
                Route::post('', [StudentGradeController::class, 'insertOrUpdate'])->middleware('can:update-student-grades')->name('lecture.student-grades.insertOrUpdate');
                Route::post('lock', [StudentGradeController::class, 'lock'])->middleware('can:update-student-grades')->name('lecture.student-grades.lock');
                Route::post('publish', [StudentGradeController::class, 'publish'])->middleware('can:update-student-grades')->name('lecture.student-grades.publish');
            });

            Route::prefix('graduations')->middleware('can:read-graduations')->group(function() {
                Route::get('', [GraduationController::class, 'index'])->name('lecture.graduations');
                Route::post('', [GraduationController::class, 'store'])->middleware('can:create-graduations')->name('lecture.graduations.store');
                Route::post('{graduation}', [GraduationController::class, 'update'])->middleware('can:update-graduations')->name('lecture.graduations.update');
                Route::delete('{graduation}', [GraduationController::class, 'destroy'])->middleware('can:delete-graduations')->name('lecture.graduations.destroy');
                Route::get('list-students', [GraduationController::class, 'getStudents'])->middleware('can:read-graduations')->name('lecturer.graduations.getStudents');
                Route::get('single-search', [GraduationController::class, 'singleSearch'])->middleware('can:read-graduations')->name('lecturer.graduations.singleSearchStudent');
                Route::get('allowed-student-statuses', [GraduationController::class, 'getAllowedStudentStatuses'])->name('lecture.graduations.allowed-student-statuses');
                Route::get('{graduation}', [GraduationController::class, 'show'])->name('lecture.graduations.show');
            });

            Route::prefix('activities')->middleware('can:read-activities')->group(function () {
                Route::post('excel', [StudentActivityController::class, 'importDataStudentActivity'])->name('lecture.activities.import');
                Route::get('', [StudentActivityController::class, 'index'])->name('lecture.activities');
                Route::get('{studentActivity}/get-students', [StudentActivityController::class, 'getStudents'])->name('lecture.activities.get-students');
                Route::get('get-employees', [StudentActivityController::class, 'getEmployees'])->name('lecture.activities.get-employees');
                Route::get('get-activity-categories', [StudentActivityController::class, 'getActivityCategories'])->name('lecture.activities.get-activity-categories');
                Route::post('', [StudentActivityController::class, 'store'])->middleware('can:create-activities')->name('lecture.activities.store');
                Route::post('{studentActivity}', [StudentActivityController::class, 'update'])->name('lecture.activities.update')->middleware('can:update-activities');
                // Route::get('download-template-import-data', [StudentActivityController::class, 'downloadTemplateImport'])->name('lecture.activities.download-template-data');
                Route::get('{studentActivity}', [StudentActivityController::class, 'show'])->name('lecture.activities.show');
                Route::delete('{studentActivity}', [StudentActivityController::class, 'destroy'])->middleware('can:delete-activities')->name('lecture.activities.delete');
                Route::prefix('{studentActivity}/members')->group(function () {
                    Route::get('', [StudentActivityController::class, 'member'])->name('lecture.activities.members');
                    Route::post('', [StudentActivityController::class, 'storeMember'])->middleware('can:create-activities')->name('lecture.activities.members.storeMember');
                    Route::get('{studentActivityMember}', [StudentActivityController::class, 'showMember'])->name('lecture.activities.members.showMember');
                    Route::post('{studentActivityMember}', [StudentActivityController::class, 'updateMember'])->middleware('can:update-activities')->name('lecture.activities.members.updateMember');
                    Route::delete('{studentActivityMember}', [StudentActivityController::class, 'destroyMember'])->middleware('can:delete-activities')->name('lecture.activities.members.destroyMember');
                });
                Route::prefix('{studentActivity}/supervisors')->group(function () {
                    Route::get('', [StudentActivityController::class, 'supervisor'])->name('lecture.activities.supervisors');
                    Route::post('', [StudentActivityController::class, 'storeSupervisor'])->middleware('can:create-activities')->name('lecture.activities.supervisors.storeSupervisor');
                    Route::get('{studentActivitySupervisor}', [StudentActivityController::class, 'showSupervisor'])->name('lecture.activities.supervisors.showSupervisor');
                    Route::post('{studentActivitySupervisor}', [StudentActivityController::class, 'updateSupervisor'])->middleware('can:update-activities')->name('lecture.activities.supervisors.updateSupervisor');
                    Route::delete('{studentActivitySupervisor}', [StudentActivityController::class, 'destroySupervisor'])->middleware('can:delete-activities')->name('lecture.activities.supervisors.destroySupervisor');
                });
            });

            Route::prefix('score-conversions')->middleware('can:read-score-conversions')->group(function () {
                Route::get('', [ActivityScoreConversionController::class, 'index'])->name('lecture.score-conversions');

                Route::prefix('{studentActivityMember}')->group(function ($data) {
                    Route::get('', [ActivityScoreConversionController::class, 'detail'])->name('lecture.score-conversions.detail');
                    Route::get('get-data', [ActivityScoreConversionController::class, 'getData'])->name('lecture.score-conversions.getData');
                    Route::post('', [ActivityScoreConversionController::class, 'store'])->middleware('can:create-score-conversions')->name('lecture.score-conversions.store');
                    Route::post('{activityScoreConversion}', [ActivityScoreConversionController::class, 'update'])->name('lecture.score-conversions.update')->middleware('can:update-score-conversions');
                    Route::get('{activityScoreConversion}', [ActivityScoreConversionController::class, 'show'])->name('lecture.score-conversions.show');
                    Route::get('{activityScoreConversion}/status', [ActivityScoreConversionController::class, 'updateStatus'])->name('lecture.score-conversions.update-status');
                    Route::delete('{activityScoreConversion}', [ActivityScoreConversionController::class, 'destroy'])->middleware('can:delete-score-conversions')->name('lecture.score-conversions.delete');
                });
            });
        });
    });
});
