<?php

namespace App\Http\Controllers\Api\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\CourseCurriculum;
use Exception;
use Illuminate\Http\Request;

class StudentCurriculumController extends Controller
{
    public function index(Request $request)
    {
        try {
            $courseCurriculums = [];
            $semesters = [];
            $query = CourseCurriculum::where('curriculum_id', getInfoLogin()->userable->curriculum_id)->with(['course'])->orderBy('semester', 'asc')->get();
            foreach ($query as $key => $value) {
                $courseCurriculums[] = [
                    'course' => $value->course->code . ' - ' . $value->course->name,
                    'credit' => $value->credit_total,
                    'is_mandatory' => $value->is_mandatory,
                    'semester' => $value->semester
                ];
                if (!in_array($value->semester, $semesters)) {
                    $semesters[] = $value->semester;
                }
            }
            $data = [
                'courses' => $courseCurriculums,
                'semester' => $semesters
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
