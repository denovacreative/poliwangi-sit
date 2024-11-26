<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use Exception;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class GeneralStudentController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (isset($request->id) && $request->id != null) {
                $student = Student::where(['id' => $request->id])->with(['employee', 'classParticipant.collegeClass', 'academicPeriod', 'classGroup', 'studentCollegeActivity', 'studyProgram.educationLevel'])->first();
            } else {
                $student = getInfoLogin()->userable;
            }
            $employee = '-';
            $nip = '-';
            $credit = 0;
            if ($student->employee_id != null) {
                $employee = $student->employee->front_title == null ? '' : $student->employee->front_title;
                $employee .= $student->employee->name;
                $nip = $student->employee->nip;
                $employee .= $student->employee->back_title == null ? '' : ', ' . $student->employee->back_title;
            }
            foreach ($student->classParticipant as $item) {
                $credit += $item->collegeClass->credit_total;
            }
            $data = [
                'nim' => $student->nim,
                'id' => $student->id,
                'name' => $student->name,
                'status' => $student->studentStatus->name,
                'academic_year' => $student->academicPeriod->academic_year_id,
                'class_group' => $student->class_group_id == null ? '-' : $student->classGroup->name,
                'employee' => $employee,
                'nip' => $nip,
                'semester' => StudentCollegeActivity::where(['student_id' => $student->id, 'student_status_id' => 'A'])->count(),
                'study_program' => $student->studyProgram->educationLevel->code . ' - ' . $student->studyProgram->name,
                'major' => $student->studyProgram->major_id != null ? $student->studyProgram->major->name :'-',
                'credit' => $credit
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function studentSemester(Request $request)
    {
        try {
            if (isset($request->id) && $request->id != null) {
                $student = Student::where(['id' => $request->id])->with(['studentCollegeActivity'])->first();
            } else {
                $student = getInfoLogin()->userable;
            }
            $academicPeriods = [];
            foreach ($student->studentCollegeActivity as $key => $value) {
                $academicPeriods[] = [
                    'hashid' => Hashids::encode($value->academicPeriod->id),
                    'id' => $value->academicPeriod->id,
                    'name' => $value->academicPeriod->name,
                    'is_use' => $value->academicPeriod->is_use
                ];
            }
            return $this->successResponse(null, compact('academicPeriods'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
