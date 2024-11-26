<?php

namespace App\Http\Controllers\Api\Lecture\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\Administration\GenerateStatusSemesterRequest;
use App\Models\AcademicPeriod;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Employee;
use App\Models\Finance;
use App\Models\Guardianship;
use App\Models\Heregistration;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudentStatus;
use App\Models\StudyProgram;
use App\Models\Transcript;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class StatusSemesterController extends Controller
{
    public function getStatusSemester(Request $request)
    {

        $programstudy = StudyProgram::with(['educationLevel'])->get();
        $academic_periods = AcademicPeriod::where('is_use', true)->first();
        $finance = Finance::all();
        // $student = Student::with(['studentStatus'])->whereHas('studentStatus', function($e){
        //     $e->where('is_college', true);
        // })->get();

        $id_priods = $request->academicPriod ? $request->academicPriod : $academic_periods->id;

        foreach ($programstudy as $key) {
            # code...
            $aktif = StudentCollegeActivity::join('students', 'student_college_activities.student_id', '=', 'students.id')
                ->join('academic_periods', 'student_college_activities.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.id', $id_priods)
                ->where('student_college_activities.student_status_id', 'A')
                ->where('students.study_program_id', $key['id'])->get();
            $cuti = StudentCollegeActivity::join('students', 'student_college_activities.student_id', '=', 'students.id')
                ->join('academic_periods', 'student_college_activities.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.id', $id_priods)
                ->where('student_college_activities.student_status_id', 'C')
                ->where('students.study_program_id', $key['id'])->get();
            $do = StudentCollegeActivity::join('students', 'student_college_activities.student_id', '=', 'students.id')
                ->join('academic_periods', 'student_college_activities.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.id', $id_priods)
                ->where('student_college_activities.student_status_id', '3')
                ->where('students.study_program_id', $key['id'])->get();
            $merdeka = StudentCollegeActivity::join('students', 'student_college_activities.student_id', '=', 'students.id')
                ->join('academic_periods', 'student_college_activities.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.id', $id_priods)
                ->where('student_college_activities.student_status_id', 'M')
                ->where('students.study_program_id', $key['id'])->get();
            $lulus = StudentCollegeActivity::join('students', 'student_college_activities.student_id', '=', 'students.id')
                ->join('academic_periods', 'student_college_activities.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.id', $id_priods)
                ->where('student_college_activities.student_status_id', '1')
                ->where('students.study_program_id', $key['id'])->get();
            $non_aktif = StudentCollegeActivity::join('students', 'student_college_activities.student_id', '=', 'students.id')
                ->join('academic_periods', 'student_college_activities.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.id', $id_priods)
                ->where('student_college_activities.student_status_id', 'N')
                ->where('students.study_program_id', $key['id'])->get();
            $no_akm = collect(DB::SELECT("select count(s.*) as total from students s, student_statuses ss where s.study_program_id='" . $key['id'] . "' and ss.id=s.student_status_id and ss.is_college=true and not exists (select sca.student_id from siakad.student_college_activities sca, siakad.academic_periods ap where sca.academic_period_id=ap.id and ap.id='" . $id_priods . "' and sca.student_id=s.id)"))->first();

            $data[] = [
                'program_study' => $key['id'],
                'program_study_name' => $key->educationLevel->code . ' - ' . $key['name'],
                'total_aktif' => $aktif->count(),
                'total_cuti' => $cuti->count(),
                'total_do' => $do->count(),
                'total_kampus_merdeka' => $merdeka->count(),
                'total_lulus' => $lulus->count(),
                'total_non_aktif' => $non_aktif->count(),
                'total_no_akm' => $no_akm->total,
                'get' => $request->academicPriod ? $request->academicPriod : [],
                'period_id' => $id_priods,
            ];
        }
        // $data['students'] = $student;

        return response()->json([
            'data' => $data,
            // 'students' => $student,
            'finance' => $finance,
        ]);
    }

    public function generate(Request $request)
    {
        try {

            $student = Student::where('study_program_id', $request->studyProgram)
                ->where('student_status_id', 'A')
                ->orWhere('student_status_id', 'M')
                ->orWhere('student_status_id', 'G')
                ->orWhere('student_status_id', 'C')
                ->orWhere('student_status_id', 'N')
                ->get();
            $academic_period = AcademicPeriod::where('is_use', true)->first();
            foreach ($student as $val) {

                $heregis = Heregistration::join('academic_periods', 'heregistrations.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.is_use', true)->where('heregistrations.student_id', $val->id)->first();
                $guardi = Guardianship::join('academic_periods', 'guardianships.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.is_use', true)->where('guardianships.student_id', $val->id)->first();

                if (!isset($heregis->id) && !isset($guardi->id)) {

                    $cek_akm = StudentCollegeActivity::where([
                        'student_id' => $val->id,
                        'academic_period_id' => $academic_period->id,
                    ])->get();

                    if (!$cek_akm) {
                        StudentCollegeActivity::create([
                            'id' => Uuid::uuid4(),
                            'academic_period_id' => $academic_period->id,
                            'student_id' => $val->id,
                            'student_status_id' => 'N',
                            'grade_semester' => 0,
                            'grade' => 0,
                            'credit_semester' => 0,
                            'credit_total' => 0,
                            'is_valid' => true
                        ]);

                        Student::where('id', $val->id)->update([
                            'student_status_id' => 'N',
                        ]);
                    }
                }
            }

            return $this->successResponse('Berhasil melakukan generate data');
        } catch (Exception $e) {

            return $this->exceptionResponse($e);
        }
    }

    public function recalculate(Request $request)
    {
        try {

            $academicPeriod = AcademicPeriod::where('is_use', true)->first();

            $student = Student::where('study_program_id', $request->studyProgram)
                ->where('student_status_id', 'A')
                ->orWhere('student_status_id', 'M')
                ->orWhere('student_status_id', 'G')
                ->orWhere('student_status_id', 'C')
                ->orWhere('student_status_id', 'N')
                ->get();

            foreach ($student as $s) {
                $cred = 0;
                $cred_semester = 0;
                $cred_index = 0;
                $grade_total = 0;
                $cred_index_semester = 0;
                $grade_total_semester = 0;
                $credit_semester = ClassParticipant::join('college_classes', 'class_participants.college_class_id', '=', 'college_classes.id')->where('college_classes.academic_period_id', $academicPeriod->id)->where('class_participants.student_id', $s->id)->sum('college_classes.credit_total');
                $credit_total = ClassParticipant::join('college_classes', 'class_participants.college_class_id', '=', 'college_classes.id')->where('class_participants.student_id', $s->id)->sum('college_classes.credit_total');
                $grade = Transcript::join('college_classes', 'transcripts.college_class_id', '=', 'college_classes.id')->where('transcripts.student_id', $s->id)->sum('transcripts.index_score');

                $grade_semester = Transcript::join('college_classes', 'transcripts.college_class_id', '=', 'college_classes.id')->where('transcripts.student_id', $s->id)->where('college_classes.academic_period_id', $academicPeriod->id)->sum('transcripts.index_score');

                if (isset($grade->index_score)) {
                    $cred += $credit_total;
                    $cred_index += ($credit_total *  $grade->index_score);
                    $grade_total = ($cred_index) / $cred;
                }


                if (isset($grade_semester->index_score)) {
                    $cred_semester += $credit_semester;
                    $cred_index_semester += ($credit_semester *  $grade_semester->index_score);
                    $grade_total_semester = ($cred_index_semester) / $cred_semester;
                }

                StudentCollegeActivity::where([
                    'student_id' => $s->id,
                    'academic_period_id' => $academicPeriod->id,
                ])->update([
                    'credit_semester' => $credit_semester,
                    'credit_total' => $credit_total,
                    'grade' => $grade_total,
                    'grade_semester' => $grade_total_semester,
                ]);
            }

            return $this->successResponse('Berhasil menghitung ulang!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function generateStudent(GenerateStatusSemesterRequest $request)
    {
        try {
            
            $student = Student::where('id', $request->student)->first();
            $academicPeriod = AcademicPeriod::where('id', $request->semester)->first();
            $student_status = StudentStatus::where('id', $request->student_status)->first();

            $akm = StudentCollegeActivity::where('student_id', $request->student)->where('academic_period_id', $request->semester)->first();

            if(!isset($akm)){
                if($student_status->id == 'C'){
                    StudentCollegeActivity::create([
                        'id' => Uuid::uuid4(),
                        'academic_period_id' => $request->semester,
                        'student_id' => $request->student,
                        'student_status_id' => $request->student_status,
                        'grade_semester' => $request->grade_semester,
                        'grade' => $request->grade,
                        'credit_semester' => $request->credit_semester,
                        'credit_total' => $request->credit_total,
                        'decree_number' => $request->decree_number,
                        'decree_date' => $request->decree_date,
                        'is_valid' => true,
                        'finance_id' => $request->finances_id,
                        'tuition_fee' => $request->tuition_fee,
                    ]);
                }else{
                    StudentCollegeActivity::create([
                        'id' => Uuid::uuid4(),
                        'academic_period_id' => $request->semester,
                        'student_id' => $request->student,
                        'student_status_id' => $request->student_status,
                        'grade_semester' => $request->grade_semester,
                        'grade' => $request->grade,
                        'credit_semester' => $request->credit_semester,
                        'credit_total' => $request->credit_total,
                        'is_valid' => true,
                        'finance_id' => $request->finances_id,
                        'tuition_fee' => $request->tuition_fee,
                    ]);
                }
            }else{
                if($student_status->id == 'C'){
                    StudentCollegeActivity::where('student_id', $request->student_id)->where('academic_period_id', $request->semester)->update([
                        'student_status_id' => $request->student_status,
                        'grade_semester' => $request->grade_semester,
                        'grade' => $request->grade,
                        'credit_semester' => $request->credit_semester,
                        'credit_total' => $request->credit_total,
                        'decree_number' => $request->decree_number,
                        'decree_date' => $request->decree_date,
                        // 'is_valid' => true,
                        'finance_id' => $request->finances_id,
                        'tuition_fee' => $request->tuition_fee,
                    ]);
                }else{
                    StudentCollegeActivity::where('student_id', $request->student_id)->where('academic_period_id', $request->semester)->update([
                        'student_status_id' => $request->student_status,
                        'grade_semester' => $request->grade_semester,
                        'grade' => $request->grade,
                        'credit_semester' => $request->credit_semester,
                        'credit_total' => $request->credit_total,
                        // 'is_valid' => true,
                        'finance_id' => $request->finances_id,
                        'tuition_fee' => $request->tuition_fee,
                    ]);
                }
            }

            return $this->successResponse('Berhasil melakukan generate data mahasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function generateValue($student,$academic_period){

        try{

            $cred = 0;
            $cred_semester = 0;
            $cred_index = 0;
            $grade_total = 0;
            $cred_index_semester = 0;
            $grade_total_semester = 0;
            $credit_semester = ClassParticipant::join('college_classes', 'class_participants.college_class_id', '=', 'college_classes.id')->where('college_classes.academic_period_id', $academic_period)->where('class_participants.student_id', $student)->sum('college_classes.credit_total');
            $credit_total = ClassParticipant::join('college_classes', 'class_participants.college_class_id', '=', 'college_classes.id')->where('class_participants.student_id', $student)->sum('college_classes.credit_total');
            $grade = Transcript::join('college_classes', 'transcripts.college_class_id', '=', 'college_classes.id')->where('transcripts.student_id', $student)->sum('transcripts.index_score');

            $grade_semester = Transcript::join('college_classes', 'transcripts.college_class_id', '=', 'college_classes.id')->where('transcripts.student_id', $student)->where('college_classes.academic_period_id', $academic_period)->sum('transcripts.index_score');

            if (isset($grade->index_score)) {
                $cred += $credit_total;
                $cred_index += ($credit_total *  $grade->index_score);
                $grade_total = ($cred_index) / $cred;
            }


            if (isset($grade_semester->index_score)) {
                $cred_semester += $credit_semester;
                $cred_index_semester += ($credit_semester *  $grade_semester->index_score);
                $grade_total_semester = ($cred_index_semester) / $cred_semester;
            }

            return response()->json([
                'credit_semester' => $credit_semester,
                'credit_total' => $credit_total,
                'grade' => $grade_total,
                'grade_semester' => $grade_total_semester,
            ]);
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }

    }

    public function getDetailStudent($study_program, $period, $status_student){

        if($status_student != 'nothing'){
            $query = StudentCollegeActivity::join('students', 'student_college_activities.student_id', '=', 'students.id')
            ->join('study_programs', 'study_programs.id', '=', 'students.study_program_id')
            ->join('academic_periods', 'student_college_activities.academic_period_id', '=', 'academic_periods.id')->where('academic_periods.id', $period)
            ->join('student_statuses', 'student_statuses.id', '=', 'students.student_status_id')
            ->where('student_college_activities.student_status_id', $status_student)
            ->where('students.study_program_id', $study_program)
            ->get(['student_college_activities.id','nim', 'students.id as student_id', 'students.name', 'student_college_activities.grade_semester', 'student_college_activities.grade', 'student_college_activities.credit_total', 'student_college_activities.credit_semester', 'academic_periods.name as period', 'study_programs.name as program', 'student_statuses.name as status']);
        }else{
            $query = collect(DB::SELECT("select s.name, s.id, s.name as status, s.nim, sp.name as program  from students s, student_statuses ss, study_programs sp  where s.study_program_id='" . $study_program . "' and sp.id=s.study_program_id and ss.id=s.student_status_id and ss.is_college=true and not exists (select sca.id, ap.name as period, sca.grade, sca.grade_semester, sca.credit_total, sca.credit_semester from siakad.student_college_activities sca, siakad.academic_periods ap where sca.academic_period_id=ap.id and ap.id='" . $period . "' and sca.student_id=s.id)"))->all();
        }

        return DataTables::of($query)->make();
    }

    public function showDataDetailStudent(StudentCollegeActivity $studentCollegeActivity){
        try{
            return response()->json([
                'data' => $studentCollegeActivity,
                'student' => Student::where('id', $studentCollegeActivity->student_id)->first(),
                'academic_period' => AcademicPeriod::where('id', $studentCollegeActivity->academic_period_id)->first(),
                'status' => StudentStatus::where('id', $studentCollegeActivity->student_status_id)->first(),
            ]);
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function updateDetailAkm(Request $request){
        try{
            $akm = StudentCollegeActivity::where('id', $request->student_akm_id)->first();
            // return response()->json([
            //     'data' => $request->tuition_fee,
            // ], 500);

            $req = StudentCollegeActivity::where('id', $request->student_akm_id)->update([
                'student_status_id' => $request->student_status,
                'grade_semester' => $request->grade_semester,
                'grade' => $request->grade,
                'credit_semester' => $request->credit_semester,
                'credit_total' => $request->credit_total,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'finance_id' => $request->finances_id,
                'tuition_fee' => $request->tuition_fee,
            ]);
            
            return $this->successResponse('Berhasil melakukan update data akm');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function singleSearch(Request $request)
    {
        return response()->json([
            'students' => Student::where('name', 'iLike', '%' . $request->name . '%')->whereHas('studentStatus', function($e){
                $e->where('is_college', true);
            })->get(),
        ]);
    }
    public function deleteDetail(StudentCollegeActivity $studentCollegeActivity){
        try{

            $studentCollegeActivity->delete();

            return $this->successResponse('Berhasil menghapus data akm');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
