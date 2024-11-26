<?php

namespace App\Http\Controllers\Api\Lecturer\Schedule;

use App\Http\Controllers\Controller;
use App\Models\CollegeClass;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LecturerScheduleController extends Controller
{
    public function index(Request $request)
    {

        $query = CollegeClass::whereHas('teachingLecturer', function ($q) {
            $q->where('employee_id', getInfoLogin()->userable->id);
        })->where('academic_period_id', getActiveAcademicPeriod()->id)
            ->with(['course', 'weeklySchedule.room', 'weeklySchedule.day', 'teachingLecturer.employee', 'studyProgram.educationLevel', 'collegeContract']);

        return DataTables::of($query)->addColumn('course_code', function ($data) {
            return $data->course->code;
        })->make();
    }
}
