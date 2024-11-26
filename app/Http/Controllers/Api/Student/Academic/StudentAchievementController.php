<?php

namespace App\Http\Controllers\Api\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentAchievementController extends Controller
{
    public function index(Request $request)
    {
        $query = Achievement::where('student_id', getInfoLogin()->userable->id)->with(['achievementGroup', 'achievementType', 'academicPeriod', 'achievementLevel']);
        if ($request->has('academic_period_id') and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }

        return DataTables::of($query)->make();
    }
}
