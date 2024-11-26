<?php

namespace App\Http\Controllers\Api\Lecturer\Guardianship;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicPeriod;
use App\Models\ClassGroup;
use App\Models\Guardianship;
use App\Models\Heregistration;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudyProgram;
use App\Models\StudyProgramSetting;
use App\Models\UniversityProfile;
use Carbon\Carbon;
use DB;
use DataTables;
use Exception;
use PhpParser\Builder\Class_;

class GuardianshipController extends Controller
{

    public function index()
    {
        $academicPeriod = AcademicPeriod::whereIsActive(true)->first();
        $student = Student::with(['studyProgram.educationLevel', 'studentCollegeActivity' => function ($q) use ($academicPeriod) {
            $q->where('academic_period_id', $academicPeriod->id);
        }])->whereEmployeeId(getInfoLogin()->userable_id)->whereHas('studentStatus', function ($q) {
            $q->where('is_college', true);
        });

        return DataTables::of($student)->addColumn('is_guardianship', function ($item) use ($academicPeriod) {
            $checkIsGuardian = StudyProgramSetting::where('study_program_id', $item->study_program_id)->whereAcademicPeriodId($academicPeriod->id)->whereIsGuardianship(true);
            $checkGuardianship = Guardianship::whereStudentId($item->id)->whereAcademicPeriodId($academicPeriod->id)->count();
            $checkHeregistration = Heregistration::whereAcademicPeriodId($academicPeriod->id)->whereStudentId($item->id)->whereIsAcc(true);
            $checkScore = Score::where('student_id', $item->id)->whereHas('collegeClass', function ($q) use ($academicPeriod) {
                $q->where('academic_period_id', $academicPeriod->id);
            })->whereIn('final_grade', ['D', 'E']);

            if ($checkHeregistration->count() > 0) {
                if ($checkIsGuardian->count() > 0 && $checkScore->count() <= 0 && $checkGuardianship <= 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        })->addColumn('heregistration', function ($item) use ($academicPeriod) {
            $checkHeregistration = Heregistration::whereAcademicPeriodId($academicPeriod->id)->whereStudentId($item->id)->whereIsAcc(true);

            return ($checkHeregistration->count() > 0);
        })->make();
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'is_acc' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $academicPeriod = AcademicPeriod::whereIsActive(true)->first();
            $studentCollegeActivity = StudentCollegeActivity::whereStudentId($request->id)->first();

            // insert on guardianship table
            $guardianship = Guardianship::create([
                'academic_period_id' => $academicPeriod->id,
                'student_id' => $request->id,
                'employee_id' => getInfoLogin()->userable_id,
                'date' => Carbon::now(),
                'grade_semester' => $studentCollegeActivity->grade_semester,
                'grade' => $studentCollegeActivity->grade,
                'credit_semester' => $studentCollegeActivity->credit_semester,
                'credit_total' => $studentCollegeActivity->credit_total,
                'guidance' => null,
                'guidance_description' => $request->description,
                'is_acc' => $request->is_acc
            ]);

            // update student status
            Student::find($request->id)->update(['student_status_id' => 'A']);
            StudentCollegeActivity::whereId($studentCollegeActivity->id)->update(['student_status_id' => 'A']);
            DB::commit();

            return $this->successResponse('Berhasil memperbarui data', compact('academicPeriod'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }


    public function print(Request $request)
    {
        try {
            $univ_profile = UniversityProfile::all();
            $isAcc = true;
            $query = Guardianship::with(['student', 'academicPeriod', 'employee', 'student.classGroup', 'student.studyProgram', 'student.studyProgram.educationLevel']);


            if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id != 'all') {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->whereHas('studyProgram', function ($q) use ($request) {
                        $q->where('id', $request->study_program_id);
                    });
                });

            }

            if ($request->has('academic_period_id') && $request->academic_period_id != null && $request->academic_period_id != 'all') {
                $query->whereHas('academicPeriod', function ($q) use ($request) {
                    $q->where('id', $request->academic_period_id);
                });
            }

            if ($request->has('class_grup_id') && $request->class_grup_id != null && $request->class_grup_id != 'all') {
                $classGroupId = ClassGroup::findByHashid($request->class_grup_id)->id;
                $query->whereHas('student', function ($q) use ($classGroupId) {
                    $q->whereHas('classGroup', function ($q) use ($classGroupId) {
                        $q->where('id', $classGroupId);
                    });
                });

            }



            if ($request->has('status_guardianship') && $request->status_guardianship != null && $request->status_guardianship != 'all') {
                $isAcc = false;
                $query->where('is_acc', $request->status_guardianship);
            }
            return view('print.report-guardianship', [
                'title' => 'Poliwangi',
                'universitasProfile' => $univ_profile,
                'data' => $query->get(),
                'periodAcadmic' =>  AcademicPeriod::where('id', $request->academic_period_id)->first(),
                'error' => $query->count() > 0 ? false : true,
                'isAcc' => $isAcc

            ]);
        } catch (Exception $e) {
            return view('print.report-guardianship', [
                'title' => 'Poliwangi',
                'universitasProfile' => $univ_profile,
                'error' => true
            ]);
        }
    }
}
