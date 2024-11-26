<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Score;
use App\Models\ScoreScale;
use App\Models\ScorePercentage;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\University;
use App\Models\UniversityProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Vinkla\Hashids\Facades\Hashids;

class StudentGradeController extends Controller
{

    public function index(CollegeClass $collegeClass)
    {
        try {
            $classParticipant = ClassParticipant::whereCollegeClassId($collegeClass->id)->with(['student' => function ($q) use ($collegeClass) {
                $q->with(['score' => function ($q) use ($collegeClass) {
                    $q->whereCollegeClassId($collegeClass->id);
                }]);
            }])->get();
            $scorePercentage = ScorePercentage::whereCollegeClassId($collegeClass->id)->with('collegeClass')->first();
            return $this->successResponse(null, compact('classParticipant', 'scorePercentage', 'collegeClass'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Request $request, CollegeClass $collegeClass)
    {
        $request->validate([
            'score_percentage_quiz' => 'required|numeric',
            'score_percentage_course_work' => 'required|numeric',
            'score_percentage_attendance' => 'required|numeric',
            'score_percentage_mid_exam' => 'required|numeric',
            'score_percentage_final_exam' => 'required|numeric',
            'score_percentage_practice' => 'required|numeric',
        ]);

        try {
            $batchInsert = [];
            DB::beginTransaction();

            $scorePercentage = ScorePercentage::whereCollegeClassId($collegeClass->id);
            if ($scorePercentage->count() > 0) {
                $scorePercentage->update([
                    'quiz' => $request->score_percentage_quiz,
                    'coursework' => $request->score_percentage_course_work,
                    'attendance' => $request->score_percentage_attendance,
                    'mid_exam' => $request->score_percentage_mid_exam,
                    'final_exam' => $request->score_percentage_final_exam,
                    'practice' => $request->score_percentage_practice
                ]);
            } else {
                $data = ScorePercentage::create([
                    'college_class_id' => $collegeClass->id,
                    'quiz' => $request->score_percentage_quiz,
                    'coursework' => $request->score_percentage_course_work,
                    'attendance' => $request->score_percentage_attendance,
                    'mid_exam' => $request->score_percentage_mid_exam,
                    'final_exam' => $request->score_percentage_final_exam,
                    'practice' => $request->score_percentage_practice
                ]);
            }

            foreach ($request->student_id as $key => $value) {
                // check
                $checkScore = Score::whereStudentId($request->student_id[$key])->whereCollegeClassId($collegeClass->id);
                $scoreScale = ScoreScale::where('study_program_id', $collegeClass->study_program_id);

                if ($scoreScale->count() > 0) {
                    $dateNow = Carbon::now();
                    $scoreScale = $scoreScale->where('date_start', '<=', $dateNow)->where('date_end', '>=', $dateNow);
                    if ($scoreScale->count() <= 0) {
                        return response()->json([
                            'message' => 'Data skala nilai telah expired'
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'message' => 'Skala nilai belum di setting'
                    ], 500);
                }
                $scoreScale = $scoreScale->where('min_score', '<=', ceil($request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key]))->where('max_score', '>=', ceil($request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key]));
                $scoreScale = $scoreScale->first();

                if ($checkScore->count() > 0) {
                    $score = $checkScore->first();
                    $checkScore->update([
                        'student_id' => $request->student_id[$key],
                        'mid_exam' => $request->mid_exam[$key] ?? 0,
                        'final_exam' => $request->final_exam[$key] ?? 0,
                        'coursework' => $request->course_work[$key] ?? 0,
                        'quiz' => $request->quiz[$key] ?? 0,
                        'attendance' => $request->attendance[$key] ?? 0,
                        'practice' => $request->practice[$key] ?? 0,
                        'score' => $request->score[$key],
                        'final_score' => $request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key],
                        'grade' => $request->remedial_score[$key] == 0 ? $scoreScale->grade : $score->grade,
                        'final_grade' => $scoreScale->grade,
                    ]);
                } else {
                    $batchInsert[] = [
                        'id' => Str::uuid(),
                        'college_class_id' => $collegeClass->id,
                        'student_id' => $request->student_id[$key],
                        'mid_exam' => $request->mid_exam[$key] ?? 0,
                        'final_exam' => $request->final_exam[$key] ?? 0,
                        'coursework' => $request->course_work[$key] ?? 0,
                        'quiz' => $request->quiz[$key] ?? 0,
                        'attendance' => $request->attendance[$key] ?? 0,
                        'practice' => $request->practice[$key] ?? 0,
                        'score' => $request->score[$key],
                        'final_score' => $request->score[$key],
                        'grade' => $scoreScale->grade,
                        'final_grade' => $scoreScale->grade,
                    ];
                }
            }

            Score::insert($batchInsert);
            DB::commit();

            return $this->successResponse('Berhasil menyimpan data', compact('scoreScale', 'score'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function lock(CollegeClass $collegeClass)
    {
        try {
            $collegeClass->update([
                'is_lock_score' => !$collegeClass->is_lock_score
            ]);

            return $this->successResponse($collegeClass->is_lock_score ? 'Berhasil mengunci nilai' : 'Berhasil membuka nilai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function publishScore(CollegeClass $collegeClass)
    {
        try {
            $score = Score::whereCollegeClassId($collegeClass->id)->first();
            Score::whereCollegeClassId($collegeClass->id)->update([
                'is_publish' => !$score->is_publish
            ]);

            return $this->successResponse($score->is_publish ? 'Nilai telah di publish' : 'Berhasil Menyembunyikan Nilai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function printGpaRank(Request $request)
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

            return view('print.grade-gpa-rank', [
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

    public function printGPA(Request $request) {

        $studyProgram = '';
        $academicYear = '';
        $classGroup = 'SEMUA KELAS GRUP';
        $academicPeriode = '';
        $query = Score::with(['student', 'collegeClass', 'student.academicPeriod']);
        try{
            if ($request->has('study_program_id') && $request->study_program_id != '' ) {
                $query->whereHas('student', function ($q) use ($request){
                    $q->where('study_program_id', $request->study_program_id);
                });
                $studyProgram =  StudyProgram::where('id', $request->study_program_id)->first()->educationLevel->code . '-' . StudyProgram::where('id', $request->study_program_id)->first()->name;
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != '' ) {
                $query->whereHas('student', function ($q) use ($request){
                    $q->whereHas('academicPeriod', function ($q) use ($request){
                    $q->where('academic_year_id', $request->academic_year_id);
                });
            });
            $academicYear = AcademicYear::where('id', $request->academic_year_id)->first()->name;
            }


            if ($request->has('class_group_id') && $request->class_group_id != '' && $request->class_group_id != 'all') {
                $class_group_id = Hashids::decode($request->class_group_id);
                   $query->whereHas('student', function ($q) use ($class_group_id) {
                       $q->where('class_group_id', $class_group_id);
                   });
               $classGroup = ClassGroup::where('id', $class_group_id)->first()->name;
           }

            if ($request->has('academic_period_id') && $request->academic_period_id != '' ) {
                $query->whereHas('collegeClass', function ($q) use ($request){
                    $q->where('academic_period_id', $request->academic_period_id);
                });
                $academicPeriode = AcademicPeriod::where('id', $request->academic_period_id)->first()->name;
            }


            $header = [
                'class_group' => $classGroup,
                'study_program' => $studyProgram,
                'academic_year_id' => $academicYear,
                'academic_period' => $academicPeriode
            ];
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
                        'angkatan' => $student->academicPeriod->academicYear->name,
                        'nim' => $student->nim,
                        'dosen_wali' => $employee,
                        'ipk' => number_format($mutu / $credit, 2)
                    ];

                    $credit = 0;
                    $mutu = 0;
                }
            }

            return view('print.grade-gpa', [
                'title' => 'Laporan Nilai Mahasiswa | Poliwangi',
                'universitasProfile' => UniversityProfile::first(),
                'datas' => $data,
                'header' => $header
            ]);
        }catch(Exception $e){
            return abort(404);
        }

    }
}
