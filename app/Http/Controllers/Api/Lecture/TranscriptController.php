<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudyProgram;
use App\Models\Transcript;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class TranscriptController extends Controller
{
    public function index(Request $request){
        try{

            $query = StudyProgram::where('is_active', true);

            if(isset($request->study_program) || $request->study_program != ''){

                $query->where('id', $request->study_program);

            }

            foreach($query->get() as $item){

                $year = AcademicPeriod::with(['academicYear'])->where('is_active', true);
                
                if(isset($request->academic_year) || $request->academic_year != ''){
                    $year->whereHas('academicYear', function($e)use($request){
                        $e->where('id', $request->academic_year);
                    });
                }

                foreach ($year->get() as $key) {
                    $allStudent = Student::where('study_program_id', $item->id)->where('academic_period_id', $key->id)->count();
                    // $totalStudent = Transcript::with('student')->whereHas('student', function($e)use($key, $item){
                    //     $e->where('academic_period_id', $key->id)->where('study_program_id', $item->id);
                    // })->count();
                    $totalStudent = collect(DB::SELECT("select count(s.*) as total from students s where s.study_program_id='" . $item->id . "' and s.academic_period_id='".$key->id."' and exists (select sca.student_id from siakad.transcripts sca where sca.student_id=s.id)"))->first();
                    if($allStudent > 0){
                        $data[] = [
                            'id' => $item->id,
                            'idYear' => $key->academicYear->id,
                            'periodName' => $key->name,
                            'studyProgram' => $item->name,
                            'academicYear' => $key->academicYear->name,
                            'allStudent' => ($allStudent),
                            'totalStudent' => ($totalStudent->total),
                        ];
                    }
                }
                
            }
            return DataTables::of($data)->make();
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function update($studyProgram, $academicYear){
        try{
            $academicPeriodNow = AcademicPeriod::where('is_use', true)->first();

            $data = Score::with('collegeClass', 'student.academicPeriod');

            $data->whereHas('collegeClass', function($e)use($academicPeriodNow){
                $e->where('academic_period_id', $academicPeriodNow->id);
            });

            $data->whereHas('student.academicPeriod', function($e)use($academicYear){
                $e->where('academic_year_id', $academicYear);
            });
            
            $data->whereHas('student', function($e)use($studyProgram){
                $e->where('study_program_id', $studyProgram);
            });


            foreach($data->get() as $item){

                $check = Transcript::where('college_class_id', $item->college_class_id)->first();

                if(isset($check->id)){

                    Transcript::where('college_class_id', $item->college_class_id)->update([
                        'credit' => $item->collegeClass->credit_total,
                        'score' => $item->final_score,
                        'grade' => $item->final_grade,
                        'index_score' => $item->index_score,
                    ]);
                    
                }else{

                    $akm = StudentCollegeActivity::where('student_id', $item->student_id)->count();

                    Transcript::create([
                        'id' => Uuid::uuid4(),
                        'student_id' => $item->student_id,
                        'course_id' => $item->collegeClass->course_id,
                        'college_class_id' => $item->collegeClass->id,
                        'credit' => $item->collegeClass->credit_total,
                        'semester' => $akm,
                        'score' => $item->final_score,
                        'grade' => $item->final_grade,
                        'index_score' => $item->index_score,
                    ]);

                }

            }

            return $this->successResponse('Berhasil update data transcript');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}