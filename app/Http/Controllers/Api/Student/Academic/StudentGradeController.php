<?php

namespace App\Http\Controllers\Api\Student\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicPeriod;
use App\Models\ClassGroup;
use App\Models\CollegeClass;
use App\Models\StudentCollegeActivity;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\University;
use App\Models\UniversityProfile;
use Exception;
use Vinkla\Hashids\Facades\Hashids;

class StudentGradeController extends Controller
{

    // public function getAcademicPeriod()
    // {
    //     try {
    //         $studentCollegeActivity = StudentCollegeActivity::where('student_id', getInfoLogin()->userable_id)->orderBy('academic_period_id', 'asc')->first();
    //         $academicPeriods = AcademicPeriod::whereIsActive(true)->where('id', '>=', (date('Y', strtotime(getInfoLogin()->userable->entry_date)) + 1) ?? $studentCollegeActivity->academic_period_id)->get();

    //         return $this->successResponse(null, compact('academicPeriods'));
    //     } catch (Exception $e) {
    //         return $this->exceptionResponse($e);
    //     }
    // }

    public function getScore(AcademicPeriod $academicPeriod)
    {
        try {
            $scores = Score::whereHas('collegeClass', function ($q) use ($academicPeriod) {
                $q->where('academic_period_id', $academicPeriod->id);
            })->whereStudentId(getInfoLogin()->userable_id)->whereIsPublish(true)->get();

            $scores = $scores->map(function ($data) {
                $data->course_code = $data->collegeClass->course->code;
                $data->course_name = $data->collegeClass->course->name;
                return $data;
            });

            return $this->successResponse(null, compact('scores'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function printGradepointaverageRank(Request $request)
    {
        try {
            $academicPeriode = '';
            $studyProgram = 'SEMUA PROGRAM STUDI';
            $classGroup = 'SEMUA GRUP KELAS';

            $query = Score::with(['student', 'collegeClass']);

            if ($request->has('study_program_id') && $request->study_program_id != '' && $request->study_program_id != 'all') {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('study_program_id', $request->study_program_id);
                });
                $studyProgram = StudyProgram::where('id', $request->study_program_id)->first()->educationLevel->code . '-' . StudyProgram::where('id', $request->study_program_id)->first()->name;
            }

            if ($request->has('class_group_id') && $request->class_group_id != '' && $request->class_group_id != 'all') {
                $class_group_id = Hashids::decode($request->class_group_id);
                $query->whereHas('student', function ($q) use ($class_group_id) {
                    $q->where('class_group_id', $class_group_id);
                });
                $classGroup = ClassGroup::where('id', $class_group_id)->first()->name;
            }

            if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'all') {
                $query->whereHas('collegeClass', function ($q) use ($request) {
                    $q->where('academic_period_id', $request->academic_period_id);
                });
                $academicPeriode = AcademicPeriod::where('id', $request->academic_period_id)->first()->name;
            }

            $data = [];
            $credit = 0;
            $mutu = 0;
            $studentIds = [];

            foreach ($query->get() as $value) {
                if (!in_array($value->student_id, $studentIds)) {
                    $studentIds[] = $value->student_id;

                    $student = Student::where('id', $value->student_id)->first();
                    $credit += $value->collegeClass->credit_total;
                    $mutu += $value->index_score * $value->collegeClass->credit_total;
                    $employee = '';

                    if ($student->employee != null) {
                        if ($student->employee_id != null) {
                            $employee = $student->employee->front_title == null ? '' : str_replace(',', '., ', $student->employee->front_title) . ' ';
                            $employee .= $student->employee->name;
                            $employee .= $student->employee->back_title == null ? '' : ', ' .  str_replace(',', '., ', $student->employee->back_title);
                        }
                    }

                    $data[] = [
                        'student_id' => $student->id,
                        'class' => $value->collegeClass->name,
                        'prodi' => $student->studyProgram->educationLevel->code . '-' . $student->studyProgram->name,
                        'nama_mahasiswa' => $student->name,
                        'nim' => $student->nim,
                        'dosen_wali' => $employee,
                        'ipk' => number_format($mutu / $credit, 2)
                    ];

                    $credit = 0;
                    $mutu = 0;
                }
            }
            $ipkValues = array_column($data, 'ipk');
            array_multisort($ipkValues, SORT_DESC, $data);

            $header = [
                'class_group' => $classGroup,
                'study_program' => $studyProgram,
                'academic_period' => $academicPeriode
            ];

            return view('print.grade-point-average-rank', [
                'title' => 'Laporan Rangking IPK | Poliwangi',
                'universitasProfile' => UniversityProfile::first(),
                'datas' => $data,
                'header' => $header
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function printStudentScores(Request $request)
    {

        $query = Score::with(['collegeClass', 'employee', 'student']);
        $academicPeriode = '';
        $studyProgram = '';
        try {
            if ($request->has('study_program_id') && $request->study_program_id != null) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('study_program_id', $request->study_program_id);
                });
                $studyProgram =  StudyProgram::where('id', $request->study_program_id)->first()->educationLevel->code . '-' . StudyProgram::where('id', $request->study_program_id)->first()->name;
            }

            if ($request->has('academic_period_id') && $request->academic_period_id != null) {
                $query->whereHas('collegeClass', function ($q) use ($request) {
                    $q->where('academic_period_id', $request->academic_period_id);
                });
                $academicPeriode = AcademicPeriod::where('id', $request->academic_period_id)->first()->name;
            }

            if ($request->has('course_id') && $request->course_id != null) {
                $query->whereHas('collegeClass', function ($q) use ($request) {
                    $q->where('course_id', $request->course_id);
                });
                $class = CollegeClass::where('course_id', $request->course_id)->first()->course->name . ' - ' . CollegeClass::where('course_id', $request->course_id)->first()->name;
            }

            $header = [
                'course' => $class,
                'study_program' => $studyProgram,
                'academic_period' => $academicPeriode
            ];

            return view('print.student-grade', [
                'title' => 'Laporan Nilai Mahasiswa | Poliwangi',
                'universitasProfile' => UniversityProfile::first(),
                'datas' => $query->get(),
                'header' => $header
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }
}
