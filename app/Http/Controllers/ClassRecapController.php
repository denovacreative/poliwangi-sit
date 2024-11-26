<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Room;
use App\Models\TimeSlot;
use Exception;
use Faker\Core\DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ClassRecapController extends Controller
{
    public function index(Request $request){
        try{
            $now = Carbon::now();
            $query = ClassSchedule::with(['collegeClass', 'room', 'collegeClass.course', 'collegeClass.studyProgram', 'meetingType'])->where('date', $now);
            $rooms = Room::all();
            if ($request->has('class_schedule_date') && $request->class_schedule_date != null && $request->class_schedule_date != 'all') {
            $query = ClassSchedule::with(['collegeClass', 'room', 'collegeClass.course', 'collegeClass.studyProgram', 'meetingType'])->where('date', $request->class_schedule_date);
            }

            $class = CollegeClass::all();
            $data = [
                'class_schedules' => $query->get(),
                'rooms' => $rooms,
            ];
            return $this->successResponse(null,compact('data'));
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
