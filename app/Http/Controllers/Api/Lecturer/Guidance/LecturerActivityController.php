<?php

namespace App\Http\Controllers\Api\Lecturer\Guidance;

use App\Http\Controllers\Controller;
use App\Models\StudentActivity;
use App\Models\StudentActivityMember;
use App\Models\StudentActivitySupervisor;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LecturerActivityController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = getInfoLogin()->userable;
        $query = StudentActivitySupervisor::where(['employee_id' => $lecturer->id])->with(['employee', 'studentActivity.studyProgram.educationLevel', 'studentActivity.studentActivityCategory', 'studentActivity.academicPeriod', 'activityCategory']);
        if ($request->has('academic_period_id') && $request->academic_period_id != null && $request->academic_period_id != 'all') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        if ($request->has('student_activity_category_id') && $request->student_activity_category_id != null && $request->student_activity_category_id != 'all') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('student_activity_category_id', $request->student_activity_category_id);
            });
        }

        return DataTables::of($query->get())->make();
    }
    public function getMember(StudentActivity $studentActivity, Request $request)
    {
        $query = StudentActivityMember::where(['student_activity_id' => $studentActivity->id])->with(['student.academicPeriod']);
        return DataTables::of($query)->make();
    }
    public function getPembimbing(StudentActivity $studentActivity, Request $request)
    {
        $query = StudentActivitySupervisor::where(['student_activity_id' => $studentActivity->id, 'role_type' => 0])->with(['employee', 'activityCategory']);
        return DataTables::of($query)->make();
    }
    public function getPenguji(StudentActivity $studentActivity, Request $request)
    {
        $query = StudentActivitySupervisor::where(['student_activity_id' => $studentActivity->id, 'role_type' => 1])->with(['employee', 'activityCategory']);
        return DataTables::of($query)->make();
    }

    public function showStudentActivity(StudentActivity $studentActivity)
    {
        try {
            $data = [
                'studyProgram' => $studentActivity->studyProgram->educationLevel->code . ' - ' . $studentActivity->studyProgram->name,
                'decreeNumber' => $studentActivity->decree_number,
                'activityType' => $studentActivity->studentActivityCategory->name,
                'title' => $studentActivity->name,
                'location' => $studentActivity->location,
                'semester' => $studentActivity->academicPeriod->name,
                'decreeDate' => $studentActivity->decree_date,
                'type' => $studentActivity->type == '1' ? 'Personal' : 'Kelompok',
                'description' => $studentActivity->description,
                'period' => $studentActivity->start_date . ' s/d ' . $studentActivity->end_date
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
