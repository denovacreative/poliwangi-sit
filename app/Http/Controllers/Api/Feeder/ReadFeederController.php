<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\Curriculum;
use App\Models\Employee;
use App\Models\Graduation;
use App\Models\Score;
use App\Models\ScoreScale;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentActivityMember;
use App\Models\StudentActivitySupervisor;
use App\Models\StudentCollegeActivity;
use App\Models\StudyProgram;
use App\Models\TeachingLecturer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class ReadFeederController extends Controller
{
    public function getStudyPrograms(Request $request)
    {
        return DataTables::of(StudyProgram::with(['major', 'educationLevel', 'academicPeriod']))->addColumn('major', function ($data) {
            return $data->major_id == null ? '-' : $data->major->name;
        })->make();
    }

    public function getScoreScales(Request $request)
    {
        $query = ScoreScale::with(['studyProgram.educationLevel']);
        if (!empty($request->study_program_id) and $request->study_program_id != '' and $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getCurriculums(Request $request)
    {
        $query = Curriculum::with(['studyProgram.educationLevel', 'academicPeriod'])
            ->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getCourses(Request $request)
    {
        $query = Course::with(['courseType', 'studyProgram.educationLevel']);
        if (!empty($request->study_program_id) and $request->study_program_id != '' and $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if (!empty($request->course_type_id) and $request->course_type_id != '' and $request->course_type_id != 'all') {
            $query->where('course_type_id', $request->course_type_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getCourseCurriculums(Request $request)
    {
        $query = CourseCurriculum::with(['course', 'curriculum.studyProgram.educationLevel'])
            ->whereHas('curriculum', function ($q) {
                $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
            });
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query->whereHas('curriculum', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->whereHas('curriculum', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getStudents(Request $request)
    {
        $query = Student::with(['studyProgram.educationLevel', 'studentStatus', 'academicPeriod.academicYear', 'religion']);
        if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if ($request->has('student_status_id') && $request->student_status_id != null && $request->student_status_id != 'all') {
            $query->where('student_status_id', $request->student_status_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
            $query->whereHas('academicPeriod', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }
        return DataTables::of($query)->make();
    }

    public function getEmployees(Request $request)
    {
        $query = Employee::with(['religion', 'employeeActiveStatus']);
        if (!empty($request->gender) and $request->gender != '' and $request->gender != 'all') {
            $query->where(['gender' => $request->gender]);
        }
        if (!empty($request->religion_id) and $request->religion_id != '' and $request->religion_id != 'all') {
            $query->where(['religion_id' => Hashids::decode($request->religion_id)[0]]);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        if (!empty($request->employee_active_status_id) and $request->employee_active_status_id != '' and $request->employee_active_status_id != 'all') {
            $query->where(['employee_active_status_id' => Hashids::decode($request->employee_active_status_id)[0]]);
        }
        return DataTables::of($query)->make();
    }

    public function getCollegeClasses(Request $request)
    {
        $query = CollegeClass::with(['academicPeriod', 'course', 'studyProgram' => function ($q) {
            $q->with(['educationLevel']);
        }, 'classParticipant', 'teachingLecturer.employee'])
            ->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query = $query->whereAcademicPeriodId($request->academic_period_id);
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query = $query->whereStudyProgramId($request->study_program_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->addColumn('participant_count', function ($data) {
            return count($data->classParticipant);
        })->make();
    }

    public function getTeachingLecturers(Request $request)
    {
        $query = TeachingLecturer::with(['employee', 'collegeClass.academicPeriod', 'collegeClass.course', 'collegeClass.studyProgram.educationLevel'])
            ->whereHas('collegeClass', function ($q) {
                $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
            });
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query = $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query = $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getClassParticipants(Request $request)
    {
        $query = ClassParticipant::with(['student', 'collegeClass.academicPeriod', 'collegeClass.course', 'collegeClass.studyProgram.educationLevel'])
            ->whereHas('collegeClass', function ($q) {
                $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
            });
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query = $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query = $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getScores(Request $request)
    {
        $query = Score::with(['student', 'collegeClass.studyProgram.educationLevel', 'collegeClass.course', 'collegeClass.academicPeriod'])
            ->whereHas('collegeClass', function ($q) {
                $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
            });
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query = $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query = $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }
    public function getStudentCollegeActivities(Request $request)
    {
        $query = StudentCollegeActivity::with(['student.studyProgram.educationLevel', 'studentStatus', 'academicPeriod', 'student.academicPeriod'])
            ->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
            $query->whereHas('student.academicPeriod', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->whereHas('student.studyProgram', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('student_status_id') && $request->student_status_id != '') {
            $query->where('student_status_id', $request->student_status_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_status');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_status');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getGraduations(Request $request)
    {
        $query = Graduation::with(['student.studyProgram.educationLevel', 'studentStatus', 'academicPeriod', 'student.academicPeriod']);
        if ($request->has('year') && $request->year != '') {
            $query->where('year', $request->year);
        }
        if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
            $query->whereHas('student.academicPeriod', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->whereHas('student.studyProgram', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('student_status_id') && $request->student_status_id != '') {
            $query->where('student_status_id', $request->student_status_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_status');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_status');
            }
        }
        return DataTables::of($query)->make();
    }

    public function getStudentActivities(Request $request)
    {
        $query = StudentActivity::with(['academicPeriod', 'studyProgram.educationLevel', 'studentActivityCategory'])
            ->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if ($request->has('student_activity_category_id') && $request->student_activity_category_id != '') {
            $query->where('student_activity_category_id', $request->student_activity_category_id);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }
    public function getStudentActivityMembers(Request $request)
    {
        $query = StudentActivityMember::with([
            'studentActivity.academicPeriod',
            'student',
            'studentActivity.studyProgram.educationLevel',
            'studentActivity.studentActivityCategory',
        ])
            ->whereHas('studentActivity', function ($q) {
                $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
            });
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('student_activity_category_id') && $request->student_activity_category_id != '') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('student_activity_category_id', $request->student_activity_category_id);
            });
        }
        if ($request->has('role_type') && $request->role_type != '') {
            $query->where('role_type', $request->role_type);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }
    public function getStudentActivitySupervisors(Request $request)
    {
        $query = StudentActivitySupervisor::with([
            'studentActivity.academicPeriod',
            'employee',
            'activityCategory',
            'studentActivity.studyProgram.educationLevel',
            'studentActivity.studentActivityCategory',
        ])->whereHas('studentActivity', function ($q) {
            $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
        });
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        if ($request->has('student_activity_category_id') && $request->student_activity_category_id != '') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('student_activity_category_id', $request->student_activity_category_id);
            });
        }
        if ($request->has('role_type') && $request->role_type != '') {
            $query->where('role_type', $request->role_type);
        }
        if ($request->has('sync') && $request->sync != null && $request->sync != 'all') {
            if ($request->sync == 0) {
                $query->whereNotNull('feeder_id');
            } else if ($request->sync == 1) {
                $query->whereNull('feeder_id');
            }
        }
        return DataTables::of($query)->make();
    }
}
