<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Room;
use App\Models\Score;
use App\Models\Student;
use App\Models\TimeSlot;
use App\Models\UniversityProfile;
use Exception;
use Faker\Core\DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class ReportKhsController extends Controller
{
    public function index(Request $request){
        try{
            return $this->successResponse(null,compact('data'));
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function getClassGroup(Request $request, $academicYear, 
    $programStudy){

        try{
            $data = AcademicYear::where('id', $academicYear)->first();

            $getClass = ClassGroup::where('academic_year_id', $data->id)->where('study_program_id', $programStudy)->get();

            return response()->json([
                'data' => $data,
                'classGroups' => $getClass,
            ]);
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }

    }
    public function getStudents(Request $request, ClassGroup $classGroup){

        try{

            $students = Student::where('class_group_id', $classGroup->id)->where('study_program_id', $classGroup->study_program_id)->get();

            return response()->json([
                'data' => $classGroup,
                'students' => $students,
            ]);
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }

    }

    public function printKhs(Request $request){
        try{
            $academicPeriod = $request->academic_period_id;
            $studyProgram = $request->study_program_id;
            $academicYear = $request->academic_year_id;
            $classGroup = $request->class_group_id;
            $student = $request->student;

            $query = isset($classGroup) ? Hashids::decode($classGroup)[0] : null;
            if($student != 'all'){
                $query = Student::with(['classGroup', 'studyProgram.major.employee', 'academicPeriod', 'employee'])->where('class_group_id', Hashids::decode($classGroup)[0])->where('id', $student)->get();
            }else{
                $query = Student::with(['classGroup', 'studyProgram.major.employee', 'academicPeriod', 'employee'])->where('class_group_id', Hashids::decode($classGroup)[0])->where('study_program_id', $studyProgram)->get();
            }

            // dd($query);

            return view('print.report-khs', [
                'title' => 'Laporan Kartu Hasil Studi | Poliwangi',
                'universitasProfile' => UniversityProfile::with(['employee', 'viceChancellor', 'viceChancellor2', 'viceChancellor3'])->first(),
                'academicPeriod' => $academicPeriod,
                'student_lecture' => $request->student_lecture,
                'khs_number' => $request->khs_number,
                'ttd' => $request->ttd,
                'data' => $query
            ]);

        }catch(Exception $e){
            return abort(404);
        }
    }
}
