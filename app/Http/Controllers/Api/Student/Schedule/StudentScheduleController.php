<?php

namespace App\Http\Controllers\Api\Student\Schedule;

use App\Http\Controllers\Controller;
use App\Models\CollegeClass;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentScheduleController extends Controller
{
    public function index(Request $request)
    {

        $query = CollegeClass::where(['academic_period_id' => getActiveAcademicPeriod()->id])->whereHas('classParticipant', function ($q) {
            $q->where('student_id', getInfoLogin()->userable->id);
        })->with(['course', 'weeklySchedule.room', 'teachingLecturer.employee', 'weeklySchedule.day', 'collegeContract']);

        return DataTables::of($query)->addColumn('course_code', function ($data) {
            return $data->course->code;
        })->make();
    }
}
