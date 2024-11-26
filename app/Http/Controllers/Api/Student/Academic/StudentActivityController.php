<?php

namespace App\Http\Controllers\Api\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\StudentActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentActivity::whereHas('studentActivityMember', function ($q) {
            $q->where('student_id', getInfoLogin()->userable->id);
        })->with(['studentActivityMember.student', 'studentActivitySupervisor.employee', 'academicPeriod', 'studentActivityCategory']);
        if (!empty($request->academic_period_id) and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        return DataTables::of($query)->make();
    }
}
