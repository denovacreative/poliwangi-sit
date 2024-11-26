<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use App\Models\CollegeClass;
use App\Models\Day;
use App\Models\Employee;
use App\Models\LectureSystem;
use App\Models\StudyProgram;
use App\Models\TeachingLecturer;
use App\Models\UniversityProfile;
use App\Models\WeeklySchedule;
use Exception;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class ReportLectureController extends Controller
{
    public function printSchedule(Request $request)
    {
        $query = CollegeClass::with(['studyProgram', 'academicPeriod', 'lectureSystem', 'course', 'weeklySchedule', 'teachingLecturer']);
        try {
            $studyProgram = '';
            $LectureSystem = '';
            $academicPeriod = '';

            if ($request->has('study_program_id') && $request->study_program_id != '') {
                $query->where('study_program_id', $request->study_program_id);
                $studyProgram = StudyProgram::find($request->study_program_id)->educationLevel->code . '-' . StudyProgram::find($request->study_program_id)->name;
            }
            if ($request->has('lecture_system_id') && $request->lecture_system_id != '') {
                $lecture_system_id = Hashids::decode($request->lecture_system_id);
                $query->where('lecture_system_id', $lecture_system_id);
                $LectureSystem = LectureSystem::where('id', $lecture_system_id)->first()->name;
            }
            if ($request->has('academic_period_id') && $request->academic_period_id != '') {
                $query->where('academic_period_id', $request->academic_period_id);
                $academicPeriod = AcademicPeriod::find($request->academic_period_id)->name;
            }

            $header = [
                'study_program' => $studyProgram,
                'lecture_system' => $LectureSystem,
                'academic_period' => $academicPeriod,
            ];

            $allDays = Day::all();
            $combinedData = [];

            foreach ($allDays as $day) {
                $dayName = $day->name;
                $combinedData[$dayName] = ['data' => []];
            }

            foreach ($query->with('weeklySchedule.day')->get() as $value) {
                foreach ($value->weeklySchedule as $schedul) {
                    $dayName = $schedul->day->name;
                    $teachingLecturers = TeachingLecturer::where('weekly_schedule_id', $schedul->id)->get();

                    foreach ($teachingLecturers as $teachingLecturer) {
                        $combinedData[$dayName]['data'][]  = [
                            'matakul_code' => $schedul->collegeClass->course->code,
                            'sks' => $schedul->collegeClass->course->credit_total,
                            'matkul' => $schedul->collegeClass->course->name,
                            'metode' => $schedul->learning_method,
                            'dosen' => $teachingLecturer->employee->name. ' '.$teachingLecturer->employee->back_title ,
                            'jam' =>  substr($schedul->time_start, 0, 5) . ' s/d ' . substr($schedul->time_end, 0, 5),
                            'room' => $schedul->room->name. ' - '. $schedul->room->location,
                        ];
                    }
                }
            }
            // foreach ($combinedData as $dayName => $dayData) {
            //     echo "Hari: " . $dayName . "\n";
            //     foreach ($dayData['data'] as $data) {
            //         echo "Mata Kuliah Code: " . $data['matakul_code'] . "\n";
            //         echo "SKS: " . $data['sks'] . "\n";
            //         echo "Mata Kuliah: " . $data['matakul'] . "\n";
            //         echo "Dosen: " . $data['dosen'] . "\n";
            //         echo "Hari dan Jam: " . $data['hari'] . "\n";
            //         echo "Ruangan: " . $data['room'] . "\n";
            //         echo "----------------------------------\n";
            //     }
            // }
            // die;
            return view('print.report-lecture-schedul', [
                'title' => 'Laporan Jadwal Kuliah | Poliwangi',
                'combinedData' => $combinedData,
                'header' => $header,
                'universitasProfile' => UniversityProfile::first()
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function getCollegeClass(Request $request){
        try{

            $query = CollegeClass::with(['studyProgram', 'academicPeriod', 'lectureSystem', 'course', 'weeklySchedule', 'teachingLecturer']);

            if($request->has('academic_period_id') && $request->academic_period_id != ''){
                $query->where('academic_period_id', $request->academic_period_id);
            }

            if ($request->has('lecture_system_id') && $request->lecture_system_id != '' && $request->lecture_system_id != 'all') {
                $lecture_system_id = Hashids::decode($request->lecture_system_id);
                $query->where('lecture_system_id', $lecture_system_id);
            }

            return response()->json([
                'data' => $query->select('id', 'name')->get(),
            ], 200);

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function printRecapScheduleLecture(Request $request)
    {
        try{
            $query = CollegeClass::with(['studyProgram', 'academicPeriod', 'lectureSystem', 'course', 'weeklySchedule', 'teachingLecturer']);

            if($request->has('academic_period_id') && $request->academic_period_id != ''){
                $query->where('academic_period_id', $request->academic_period_id);
            }

            if ($request->has('lecture_system_id') && $request->lecture_system_id != '' && $request->lecture_system_id != 'all') {
                $lecture_system_id = Hashids::decode($request->lecture_system_id);
                $query->where('lecture_system_id', $lecture_system_id);
            }

            if ($request->has('employee_id') && $request->employee_id != '' && $request->employee_id != 'all') {
                $query->whereHas('teachingLecturer', function ($q) use($request){
                    $q->where('employee_id', $request->employee_id);
                });
            }

            return view('print.report-lecture-recap-schedule', [
                'title' => 'Laporan Rekap Jadwal Dosen | Poliwangi',
                'data' => $query->get(),
                'universitasProfile' => UniversityProfile::first(),
                'employee' => $request->employee_id,
            ]);

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
