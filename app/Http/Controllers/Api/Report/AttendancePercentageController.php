<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Presence;
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

class AttendancePercentageController extends Controller
{
    public function print(Request $request){
        try{

            $presences = [];

            $collegeClass = CollegeClass::with(['academicPeriod', 'studyProgram', 'course', 'classSchedule.employee', 'presence']);

            if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id !=  'all') {
                $collegeClass->whereHas('studyProgram', function ($q) use ($request) {
                    $q->where('id', $request->study_program_id);
                });
            }

            if ($request->has('academic_period_id') && $request->academic_period_id != null) {
                $collegeClass->where('academic_period_id', $request->academic_period_id);
            }

            return view('print.report-attendance-presentage', [
                'title' => 'Laporan Presentase Kehadiran MHS | Poliwangi',
                'universitasProfile' => UniversityProfile::with(['employee', 'viceChancellor', 'viceChancellor2', 'viceChancellor3'])->first(),
                'data' => $collegeClass->get(),
                'class_group' => $request->class_grup_id,
            ]);

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}