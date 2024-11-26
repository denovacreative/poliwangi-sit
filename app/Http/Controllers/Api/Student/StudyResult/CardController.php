<?php

namespace App\Http\Controllers\Api\Student\StudyResult;

use App\Http\Controllers\Api\Student\GeneralStudentController;
use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\ActivityScoreConversion;
use App\Models\Guardianship;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudyProgramSetting;
use App\Models\Transcript;
use App\Models\UniversityProfile;
use Carbon\Carbon;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Pdf as WriterPdf;
use PHPUnit\Framework\MockObject\Builder\Stub;

use function Termwind\render;

class CardController extends Controller
{


    public function index(Request $request)
    {
        $total_c = 0;
        $total_m = 0;
        $is_acc = false;
        try {

            $grade_point_average = DB::table('scores')
                ->join('college_classes', 'college_classes.id', '=', 'scores.college_class_id')
                ->join('courses', 'courses.id', '=', 'college_classes.course_id')->where('scores.student_id', '=', getInfoLogin()->userable->id)->where('scores.is_publish', '=', true)
                ->select(['scores.index_score', 'college_classes.credit_total', 'scores.is_publish'])
                ->get();

            $guardianships = Guardianship::where('academic_period_id', '=', $request->academic_period_id)->where('student_id', '=', 'e1bbb8f5-f120-42d0-9af8-e6203ee326a7')->where('is_acc', true)->get();

            foreach ($guardianships as $guardianship)
                if (count($guardianships) < 1) {
                    $is_acc = false;
                } else {
                    $is_acc = $guardianship->is_acc;
                }

            if (!empty($request->academic_period_id) and $request->academic_period_id != '' and  $request->academic_period_id != 'all') {
                $query  = DB::table('scores')
                    ->join('college_classes', 'college_classes.id', '=', 'scores.college_class_id')
                    ->join('courses', 'courses.id', '=', 'college_classes.course_id')->where('scores.student_id', '=', getInfoLogin()->userable->id)->where('college_classes.academic_period_id', $request->academic_period_id)->where('scores.is_publish', '=', true)
                    ->select(['courses.name', 'courses.code', 'scores.index_score', 'scores.final_grade', 'college_classes.credit_total', 'college_classes.academic_period_id'])
                    ->get();
            }


            foreach ($grade_point_average as $key => $value) {
                $total_c += $value->credit_total;
                $total_m += $value->index_score * $value->credit_total;
            }

            $data = [
                'khs' => $query,
                'total_c' => $total_c,
                'total_m' => floor($total_m),
                'is_acc' => $is_acc
            ];

            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function studentTranscript()
    {
        $courseTranscripts = [];
        $total_credit = 0;
        $total_sks_index = 0;
        $courseTranscripts_ = [];
        $total_credit_ = 0;
        $total_sks_index_ = 0;

        try {
            $idStudent = getInfoLogin()->userable->id;
            $query = Score::with(['collegeClass', 'collegeClass.course'])
                ->where('student_id',  getInfoLogin()->userable->id)->get();

            $queryConvert = ActivityScoreConversion::with(['studentActivityMember', 'studentActivity', 'course'])->whereHas('studentActivityMember', function($q)use($idStudent){
                $q->where('student_id', $idStudent);
            })->get();

            foreach ($query as $key => $value) {
                $total_credit += $value->collegeClass->credit_total;
                $total_sks_index += round($value->collegeClass->credit_total *  $value->index_score);

                $courseTranscripts[] = [
                    'course_code' => $value->collegeClass->course->code,
                    'credit' => $value->collegeClass->credit_total,
                    'course_name' => $value->collegeClass->course->name,
                    'grade' => $value->final_grade,
                    'index' => $value->index_score,
                ];
            }
            foreach ($queryConvert as $key => $value) {
                $total_credit_ += $value->credit;
                $total_sks_index_ += round($value->credit *  $value->index_score);

                $courseTranscripts_[] = [
                    'course_code' => $value->course->code,
                    'credit' => $value->credit,
                    'course_name' => $value->course->name,
                    'grade' => $value->final_grade,
                    'index' => $value->index_score,
                ];
            }
            $merge = array_merge($courseTranscripts, $courseTranscripts_);
            $data = [
                'courseTranscripts' => $merge,
                'total_credit' => ($total_credit + $total_credit_),
                'total_sks_index' => ($total_sks_index + $total_sks_index_),
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }


    public function printKhs(Request $request)
    {
        $total_credit = 0;
        $total_mutu = 0;
        $total_credit_ = 0;
        $total_mutu_ = 0;
        try {
            $signature_head_department = DB::table('students')
                ->join('study_programs', 'students.study_program_id', '=', 'study_programs.id')
                ->join('majors', 'study_programs.major_id', '=', 'majors.id')
                ->join('employees', 'majors.employee_id', '=', 'employees.id')
                ->where('students.id', $request->student_id)->select(['employees.name', 'employees.nip', 'employees.back_title', 'employees.front_title'])->get();

            $student = Student::where(['id' => $request->student_id])->with(['employee', 'classParticipant.collegeClass', 'academicPeriod', 'classGroup', 'studentCollegeActivity', 'studyProgram.educationLevel'])->first();
            $periodeAcademic = AcademicPeriod::where('id', $request->academic_period_id)->first();
            $employee = '-';
            $nip = '-';
            $credit = 0;

            foreach ($student->classParticipant as $item) {
                $credit += $item->collegeClass->credit_total;
            }

            $grade_point_average = DB::table('scores')
                ->join('college_classes', 'college_classes.id', '=', 'scores.college_class_id')
                ->join('courses', 'courses.id', '=', 'college_classes.course_id')->where('scores.student_id', '=', $request->student_id)->where('scores.is_publish', '=', true)
                ->select(['scores.index_score', 'college_classes.credit_total', 'scores.is_publish'])
                ->get();

            $grade_point_average_conv = DB::table('activity_score_conversions')
            ->join('student_activities', 'student_activities.id', '=', 'activity_score_conversions.student_activity_id')
            ->join('courses', 'courses.id', '=', 'activity_score_conversions.course_id')
            ->join('student_activity_members', 'student_activity_members.id', '=', 'activity_score_conversions.student_activity_member_id')
            ->where('activity_score_conversions.is_transcript', true)
            ->where('student_activity_members.student_id', $request->student_id)
            ->select(['activity_score_conversions.index_score', 'activity_score_conversions.credit as credit_total'])
            ->get();


            $query  = DB::table('scores')
                ->join('college_classes', 'college_classes.id', '=', 'scores.college_class_id')
                ->join('courses', 'courses.id', '=', 'college_classes.course_id')->where('scores.student_id', '=', $request->student_id)->where('college_classes.academic_period_id', $request->academic_period_id)->where('scores.is_publish', '=', true)
                ->select(['courses.name', 'courses.code', 'scores.index_score', 'scores.final_grade', 'college_classes.credit_total', 'college_classes.academic_period_id'])
                ->get();

            $queryConvertion = DB::table('activity_score_conversions')
                ->join('student_activities', 'student_activities.id', '=', 'activity_score_conversions.student_activity_id')
                ->join('courses', 'courses.id', '=', 'activity_score_conversions.course_id')
                ->join('student_activity_members', 'student_activity_members.id', '=', 'activity_score_conversions.student_activity_member_id')
                ->where('student_activities.academic_period_id', $request->academic_period_id)
                ->where('student_activity_members.student_id', $request->student_id)
                ->where('activity_score_conversions.is_transcript', true)
                ->select(['courses.name', 'courses.code', 'activity_score_conversions.index_score', 'activity_score_conversions.grade as final_grade', 'activity_score_conversions.credit as credit_total',  'student_activities.academic_period_id'])
                ->get();

            if (count($query) < 1) {
                return abort(404);
            }
            if (count($queryConvertion) < 1) {
                return abort(404);
            }

            foreach ($grade_point_average as $value) {
                $total_credit += $value->credit_total;
                $total_mutu += $value->index_score * $value->credit_total;
            }
            foreach ($grade_point_average_conv as $value) {
                $total_credit_ += $value->credit_total;
                $total_mutu_ += $value->index_score * $value->credit_total;
            }

            $dataQuery = [];
            foreach ($query as $key => $value) {
                $dataQuery[] = [
                    'name' => $value->name,
                    'code' => $value->code,
                    'index_score' => $value->index_score,
                    'final_grade' => $value->final_grade,
                    'credit_total' => $value->credit_total,
                    'academic_period_id' => $value->academic_period_id,
                ];
            }
            $dataQuery_ = [];
            foreach ($queryConvertion as $key => $value) {
                $dataQuery_[] = [
                    'name' => $value->name,
                    'code' => $value->code,
                    'index_score' => $value->index_score,
                    'final_grade' => $value->final_grade,
                    'credit_total' => $value->credit_total,
                    'academic_period_id' => $value->academic_period_id,
                ];
            }
            $dataValue = array_merge($dataQuery, $dataQuery_);



            $univ_profile = UniversityProfile::all();
            $univ_name = '';
            $univ_email = '';
            $univ_street = '';
            $univ_web = '';

            foreach ($univ_profile as $key => $value) {
                $univ_name = $value->name;
                $univ_web = $value->website;
                $univ_email = $value->email;
                $univ_street = $value->street;
            }

            return view('print.card', [
                'title' => 'Print Kartu Hasil Studi',
                'datas' => $dataValue,
                'nim' => $student->nim,
                'total_credit' => ($total_credit + $total_credit_),
                'total_mutu' => ($total_mutu + $total_mutu_),
                'nip' => $nip,
                'ketua_jurusan' => $signature_head_department[0],
                'nama_univ' => $univ_name,
                'alamat_univ' => $univ_street,
                'website_univ' => $univ_web,
                'email_univ' => $univ_email,
                'study_program' => $student->studyProgram->educationLevel->code . ' - ' . $student->studyProgram->name,
                'academic_periode_name' => $periodeAcademic->name,
                'employee' => $employee,
                'name' => $student->name,
                'class_group' => $student->class_group_id == null ? '-' : $student->classGroup->name,
                'major' => $student->studyProgram->major->name,
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function printTranscript(Request $request)
    {
        try {

            $student = Student::where(['id' => $request->student_id])->with(['employee', 'classParticipant.collegeClass', 'academicPeriod', 'classGroup', 'studentCollegeActivity', 'studyProgram.educationLevel'])->first();
            $employee = '-';
            $total_credit = 0;
            $total_sks_index = 0;
            $total_credit_ = 0;
            $total_sks_index_ = 0;


            $query = Score::with(['collegeClass', 'collegeClass.course'])
                ->where('student_id',  $request->student_id)->get();
            $convert = ActivityScoreConversion::with(['studentActivityMember', 'studentActivity', 'course'])->whereHas('studentActivityMember', function($q)use($student){
                $q->where('student_id', $student->id);
            })->get();

            $nilai = [];
            foreach ($query as $key => $value) {
                $total_credit += $value->collegeClass->credit_total;
                $total_sks_index += round($value->collegeClass->credit_total *  $value->index_score);

                $nilai[] = [
                    'kode' => $value->collegeClass->course->code,
                    'sks' => $value->collegeClass->credit_total,
                    'matakuliah' => $value->collegeClass->course->name,
                    'matakuliah_inggris' => $value->collegeClass->course->name_en,
                    'nhu' => $value->final_grade,
                    'am' => $value->index_score,
                ];
            }

            $nilaiConvert = [];
            foreach ($convert as $key => $value) {
                $total_credit_ += $value->credit;
                $total_sks_index_ += round($value->credit *  $value->index_score);

                $nilaiConvert[] = [
                    'kode' => $value->course->code,
                    'sks' => $value->credit_total,
                    'matakuliah' => $value->course->name,
                    'matakuliah_inggris' => $value->course->name_en,
                    'nhu' => $value->final_grade,
                    'am' => $value->index_score,
                ];
            }
            $merge = array_merge($nilai, $nilaiConvert);

            $univ_profile = UniversityProfile::with('viceChancellor')->get();

            $nip = '-';
            $name = '-';
            $univ_name = '';
            $univ_email = '';
            $univ_street = '';
            $univ_web = '';
            // $back_title = '';
            foreach ($univ_profile as $key => $value) {
                if ($univ_profile[0]['vice_chancellor'] != null) {
                    $nip = $value->viceChancellor->nip;
                    $name = $value->viceChancellor->front_title == null ? '' : $value->viceChancellor->front_title . ' ';
                    $name .= $value->viceChancellor->name;
                    $name .= $value->viceChancellor->back_title == null ? '' : ', ' .  str_replace(',', '., ', $value->viceChancellor->back_title);
                }
                $univ_name = $value->name;
                $univ_web = $value->website;
                $univ_email = $value->email;
                $univ_street = $value->street;
            }

            return view('print.transcript', [
                'nama' => $student->name,
                'tmplahir' => $student->birthplace,
                'tgllahir' => $student->birthdate,
                'nim' => $student->nim,
                'program_studi' => $student->studyProgram->educationLevel->code . ' - ' . $student->studyProgram->name,
                'program' =>  $student->studyProgram->educationLevel->code,
                'nilai' => $merge,
                'nama_univ' => $univ_name,
                'alamat_univ' => $univ_street,
                'website_univ' => $univ_web,
                'email_univ' => $univ_email,
                'wakil_direktur_bidang_akademik_nama' => $name,
                'wakil_direktur_bidang_akademik_nip' => $nip,
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }
}
