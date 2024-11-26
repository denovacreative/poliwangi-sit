<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Exports\activityExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\ImportStudentActivityRequest;
use App\Http\Requests\Lecture\StudentActivityMemberRequest;
use App\Http\Requests\Lecture\StudentActivityRequest;
use App\Http\Requests\Lecture\StudentActivitySupervisorRequest;
use App\Imports\StudentActivityImport;
use App\Models\ActivityCategory;
use App\Models\Employee;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentActivityMember;
use App\Models\StudentActivitySupervisor;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class StudentActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentActivity::with([
            'studyProgram.educationLevel',
            'academicPeriod',
            'studentActivityCategory'
        ])->withCount('studentActivityMember')
            ->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
        if (!empty($request->academic_period_id) and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if (!empty($request->study_program_id) and $request->study_program_id != '' and $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if (!empty($request->student_activity_category_id) and $request->student_activity_category_id != '' and $request->student_activity_category_id != 'all') {
            $query->where('student_activity_category_id', $request->student_activity_category_id);
        }
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }
        return DataTables::of($query)->make();
    }

    public function store(StudentActivityRequest $request)
    {
        try {
            StudentActivity::create([
                'id' => Uuid::uuid4(),
                'name' => $request->title,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'description' => $request->description,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'is_mbkm' => $request->mbkm,
                'study_program_id' => $request->study_program,
                'academic_period_id' => $request->semester,
                'student_activity_category_id' => $request->activity_category,
            ]);
            return $this->successResponse('Berhasil membuat aktivitas mahasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(StudentActivity $studentActivity)
    {
        return $this->successResponse(null, compact('studentActivity'));
    }

    public function update(StudentActivity $studentActivity, StudentActivityRequest $request)
    {
        try {
            $studentActivity->update([
                'name' => $request->title,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'description' => $request->description,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'is_mbkm' => $request->mbkm,
                'study_program_id' => $request->study_program,
                'academic_period_id' => $request->semester,
                'student_activity_category_id' => $request->activity_category,
            ]);
            return $this->successResponse('Berhasil mengupdate aktivitas mahasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(StudentActivity $studentActivity)
    {
        try {
            $studentActivity->delete();
            return $this->successResponse('Berhasil menghapus aktivitas mahasiswa');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function member(StudentActivity $studentActivity)
    {
        return DataTables::of(StudentActivityMember::with('student.academicPeriod')->where('student_activity_id', $studentActivity->id)->get())
            ->editColumn('role_type', function ($data) {
                return $data->role_type == 1 ? 'Ketua' : ($data->role_type == 2 ? 'Anggota' : 'Personal');
            })->make(true);
    }

    public function getStudents(StudentActivity $studentActivity)
    {
        $students = Student::select(['id', 'name', 'nim'])->where(['student_status_id' => 'A', 'study_program_id' => $studentActivity->study_program_id])->get();

        return $this->successResponse(null, compact('students'));
    }

    public function storeMember(StudentActivity $studentActivity, StudentActivityMemberRequest $request)
    {
        try {
            StudentActivityMember::create([
                'id' => Uuid::uuid4(),
                'student_id' => $request->student_id,
                'role_type' => $request->role_type,
                'student_activity_id' => $studentActivity->id
            ]);

            return $this->successResponse('Berhasil membuat data peserta aktivitas');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function showMember(StudentActivity $studentActivity, StudentActivityMember $studentActivityMember)
    {
        return $this->successResponse(null, compact('studentActivityMember'));
    }

    public function updateMember(StudentActivity $studentActivity, StudentActivityMember $studentActivityMember, StudentActivityMemberRequest $request)
    {
        try {
            $studentActivityMember->update($request->only(['student_id', 'role_type']));

            return $this->successResponse('Berhasil mengupdate data peserta aktivitas');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroyMember(StudentActivity $studentActivity, StudentActivityMember $studentActivityMember)
    {
        try {
            $studentActivityMember->delete();

            return $this->successResponse('Berhasil menghapus data peserta aktivitas');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function supervisor(StudentActivity $studentActivity, Request $request)
    {
        return DataTables::of(StudentActivitySupervisor::with(['employee', 'activityCategory'])->where('student_activity_id', $studentActivity->id)->where('role_type', $request->role_type)->get())->make(true);
    }

    public function getEmployees()
    {
        $employees = Employee::select(['id', 'front_title', 'back_title', 'name'])->get();

        return $this->successResponse(null, compact('employees'));
    }

    public function getActivityCategories()
    {
        $activityCategories = ActivityCategory::all();

        return $this->successResponse(null, compact('activityCategories'));
    }

    public function storeSupervisor(StudentActivity  $studentActivity, StudentActivitySupervisorRequest $request)
    {
        try {
            StudentActivitySupervisor::create([
                'id' => Uuid::uuid4(),
                'employee_id' => $request->employee_id,
                'role_type' => $request->role_type,
                'number' => $request->number,
                'student_activity_id' => $studentActivity->id,
                'activity_category_id' => $request->activity_category_id
            ]);

            if ($request->role_type == '0') {
                return $this->successResponse('Berhasil menambahkan data dosen pembimbing');
            } else {
                return $this->successResponse('Berhasil menambahkan data dosen penguji');
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function showSupervisor(StudentActivity $studentActivity, StudentActivitySupervisor $studentActivitySupervisor)
    {
        return $this->successResponse(null, compact('studentActivitySupervisor'));
    }

    public function updateSupervisor(StudentActivity $studentActivity, StudentActivitySupervisor $studentActivitySupervisor, StudentActivitySupervisorRequest $request)
    {
        try {
            $studentActivitySupervisor->update([
                'employee_id' => $request->employee_id,
                'number' => $request->number,
                'activity_category_id' => $request->activity_category_id
            ]);

            if ($studentActivitySupervisor->role_type == '0') {
                return $this->successResponse('Berhasil mengupdate data dosen pembimbing');
            } else {
                return $this->successResponse('Berhasil mengupdate data dosen penguji');
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroySupervisor(StudentActivity $studentActivity, StudentActivitySupervisor $studentActivitySupervisor)
    {
        try {
            $studentActivitySupervisor->delete();

            if ($studentActivitySupervisor->role_type == '0') {
                return $this->successResponse('Berhasil menghapus data dosen pembimbing');
            } else {
                return $this->successResponse('Berhasil menghapus data dosen penguji');
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function importDataStudentActivity(Request $request)
    {
        try{

            $file = $request->file('file_import');

            // membuat nama file unik
            $name_file = $file->hashName();

            //temporary file
            $path = $file->storeAs('public/excel/',$name_file);

            $res = Excel::import(new StudentActivityImport, storage_path('app/public/excel/'.$name_file));
            return $this->successResponse('data',$res);
            //remove from server
            Storage::delete($path);


            return $this->successResponse('Berhasil Import Data');
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ],500);
            // return $this->exceptionResponse([$e->getMessage()]);
        }
    }

    public function downloadTemplateImport()
    {

        try {
            return Excel::download(new ActivityExport, 'TemplateImportStudentActivity.xlsx');
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
