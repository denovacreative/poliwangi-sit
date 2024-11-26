<?php

namespace App\Http\Controllers\Api\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\CollegeClass;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StudentCourseController extends Controller
{
    public function index(Request $request)
    {
        $query = CollegeClass::whereHas('classParticipant', function ($q) {
            $q->where('student_id', getInfoLogin()->userable->id);
        })->with(['course', 'academicPeriod', 'teachingLecturer.employee']);
        if (!empty($request->academic_period_id) and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        return DataTables::of($query->get())->make();
    }
}
