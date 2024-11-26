<?php

namespace App\Http\Controllers\Api\Lecture\Administration;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\ClassGroup;
use App\Models\Employee;
use App\Models\Guardianship;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\UniversityProfile;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class GuardianshipController extends Controller
{
    public function index()
    {
        $query = Employee::whereHas('student', function ($q) {
            $q->where('student_status_id', 'A');
        })->with(['student', 'guardianship']);

        return DataTables::of($query)->addColumn('student_total', function ($data) {
            return $data->student()->where('student_status_id', 'A')->count();
        })->addColumn('finish_guardianship', function ($data) {
            return $data->guardianship()->where('academic_period_id', getActiveAcademicPeriod()->id)->count();
        })->addColumn('unfinish_guardianship', function ($data) {
            $studentTotal = $data->student()->where('student_status_id', 'A')->count();
            $guardianshipTotal = $data->guardianship()->where('academic_period_id', getActiveAcademicPeriod()->id)->count();
            return $studentTotal - $guardianshipTotal;
        })->make();
    }

    public function show(Employee $employee, Request $request)
    {

        $students = Student::where('employee_id', $employee->id)->with(['classGroup', 'guardianship' => function ($g) use ($request) {
            $g->whereHas('academicPeriod', function ($ap) use ($request) {
                if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'active') {
                    $ap->where('id', Hashids::decode($request->academic_period_id)[0]);
                } else {
                    $ap->where('is_use', true);
                }
            });
        }]);

        if ($request->has('study_program_id') && $request->study_program_id != '' && $request->study_program_id != 'all') {
            $students->where('study_program_id', $request->study_program_id);
        }

        if ($request->has('guardianship_status') && $request->guardianship_status != '' && $request->guardianship_status != 'all') {
            if ($request->guardianship_status == 'null') {
                $students->whereDoesntHave('guardianship', function ($gd) use ($request) {
                    if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'active') {
                        $gd->where('id', Hashids::decode($request->academic_period_id)[0]);
                    }
                });
            } else {
                $students->whereHas('guardianship', function ($gd) use ($request) {
                    if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'active') {
                        $gd->where('id', Hashids::decode($request->academic_period_id)[0]);
                    }
                    if ($request->guardianship_status == 'true') {
                        $gd->where('is_acc', true);
                    } else {
                        $gd->where('is_acc', false);
                    }
                });
            }
        }

        return DataTables::of($students->get())->make();
    }

    public function store(Employee $employee, Request $request)
    {
        try {
            DB::beginTransaction();

            if (!$request->has('students')) {
                return $this->errorResponse(422, 'ID Mahasiswa Diperlukan !');
            }

            Student::whereIn('id', $request->students)->update([
                'employee_id' => $employee->id
            ]);

            DB::commit();

            return $this->successResponse('Berhasil menambahkan siswa ke daftar dosen wali ini');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Employee $employee, Request $request)
    {

        if (!$request->has('students')) {
            return $this->errorResponse(422, 'ID Mahasiswa Diperlukan !');
        }

        if (is_array($request->students)) {
            Student::whereIn('id', $request->students)->update([
                'employee_id' => null,
            ]);
        } else {
            Student::find($request->students)->update([
                'employee_id' => null,
            ]);
        }

        return $this->successResponse('Berhasil menghapus siswa dari daftar dosen wali ini');
    }

    public function getStudentList(Employee $employee, Request $request)
    {
        try {

            $query = Student::with(['studyProgram.educationLevel', 'classGroup', 'studentStatus', 'academicPeriod.academicYear'])->where('student_status_id', 'A')->whereNull('employee_id')->whereDoesntHave('judicialParticipant');

            if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id != 'all') {
                $query->where('study_program_id', $request->study_program_id);
            }

            if ($request->has('class_group_id') && $request->class_group_id != null && $request->class_group_id != 'all') {
                $query->where('class_group_id', $request->class_group_id);
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
                $query->whereHas('academicPeriod', function ($q) use ($request) {
                    $q->where('academic_year_id', Hashids::decode($request->academic_year_id));
                });
            }
            if (mappingAccess() != null) {
                $query->whereIn('study_program_id', mappingAccess());
            }

            return DataTables::of($query)
            ->addColumn('akm_semester', function ($data) {
                return StudentCollegeActivity::where(['student_id' => $data->id, 'student_status_id' => 'A'])->count();
            })
            ->make();
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

            $query->whereHas('academicPeriod', function ($q) use ($request) {
                $q->where('id', $request->academic_period_id);
            });



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
            dd($e);
            return view('print.report-guardianship', [
                'title' => 'Poliwangi',
                'universitasProfile' => $univ_profile,
                'error' => true
            ]);
        }
    }
}
