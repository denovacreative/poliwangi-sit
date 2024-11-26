<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentCollegeActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentSemesterStatusController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentCollegeActivity::where(['student_id' => getInfoLogin()->userable->id])->with(['academicPeriod', 'studentStatus']);
        return DataTables::of($query->get())->make();
    }
}
