<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\EmployeeRequest;
use App\Models\CollegeClass;
use App\Models\Employee;
use App\Models\EmployeeActiveStatus;
use App\Models\EmployeeStatus;
use App\Models\EmployeeType;
use App\Models\StudentActivityMember;
use App\Models\TeachingLecturer;
use App\Models\UniversityProfile;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use DataTables;
use Exception;
use Hashids;
use Illuminate\Support\Facades\File;
use Mockery\Expectation;
use PhpParser\Node\Expr\FuncCall;

class EmployeeController extends Controller
{

    const EMPLOYEE_PICTURE_PATH = 'storage/images/employees/';

    public function index(Request $request)
    {
        $query = Employee::with(['employeeStatus', 'employeeType', 'religion', 'employeeActiveStatus']);
        if (!empty($request->gender) and $request->gender != '' and $request->gender != 'all') {
            $query->where(['gender' => $request->gender]);
        }
        if (!empty($request->employee_status_id) and $request->employee_status_id != '' and $request->employee_status_id != 'all') {
            $query->where(['employee_status_id' => Hashids::decode($request->employee_status_id)[0]]);
        }
        if (!empty($request->employee_active_status_id) and $request->employee_active_status_id != '' and $request->employee_active_status_id != 'all') {
            $query->where(['employee_active_status_id' => Hashids::decode($request->employee_active_status_id)[0]]);
        }
        if (!empty($request->employee_type_id) and $request->employee_type_id != '' and $request->employee_type_id != 'all') {
            $query->where(['employee_type_id' => Hashids::decode($request->employee_type_id)[0]]);
        }
        return DataTables::of($query)->make();
    }

    public function store(EmployeeRequest $request)
    {
        $request->merge([
            'id' => Uuid::uuid4(),
            'employee_active_status_id' => $request->has('employee_active_status_id') && $request->employee_active_status_id != '' ? Hashids::decode($request->employee_active_status_id)[0] : null,
            'employee_status_id' => $request->has('employee_status_id') && $request->employee_status_id != '' ? Hashids::decode($request->employee_status_id)[0] : null,
            'religion_id' => $request->has('religion_id') && $request->religion_id != '' ? Hashids::decode($request->religion_id)[0] : null,
            'scientific_field_id' => $request->has('scientific_field_id') && $request->scientific_field_id != '' ? Hashids::decode($request->scientific_field_id)[0] : null,
            'university_id' => $request->has('university_id') && $request->university_id != '' ? Hashids::decode($request->university_id)[0] : null,
            'employee_type_id' => $request->has('employee_type_id') && $request->employee_type_id != '' ? Hashids::decode($request->employee_type_id)[0] : null,
            'region_id' => $request->has('region_id') && $request->region_id != '' ? Hashids::decode($request->region_id)[0] : null,
            'family_profession_id' => $request->has('family_profession_id') && $request->family_profession_id != '' ? Hashids::decode($request->family_profession_id)[0] : null,
        ]);

        $pictureName = null;

        if ($request->hasFile('photo_picture')) {
            $pictureName = uniqid('pegawai-') . '.' . $request->file('photo_picture')->getClientOriginalExtension();
            $request->file('photo_picture')->move(public_path(self::EMPLOYEE_PICTURE_PATH), $pictureName);
        }

        $request->merge(['picture' => $pictureName]);

        Employee::create($request->only([
            'id', 'nip', 'nidn', 'nidk', 'nupn', 'nuptk', 'nbm', 'name', 'gender', 'birthplace', 'birthdate', 'phone_number', 'house_phone_number', 'personal_email', 'campus_email', 'front_title', 'back_title', 'street', 'neighbourhood', 'hamlet', 'village_lev_1', 'village_lev_2', 'address', 'postal_code', 'tax_number', 'tax_name', 'mother_name', 'cpns_number', 'cpns_date', 'appointment_number', 'appointment_end_date', 'family_name', 'family_nip', 'marital_status', 'employee_active_status_id', 'employee_status_id', 'employee_type_id', 'university_id', 'scientific_field_id', 'religion_id', 'country_id', 'family_profession_id', 'region_id', 'picture'
        ]));

        return $this->successResponse('Berhasil membuat data pegawai baru');
    }

    public function setRpsEmployee(Request $request)
    {
        // return $this->errorResponse(400, 'asdsad', [
        //     's' => $request->employees
        // ]);

        Employee::whereIn('id', $request->employees)->update([
            'is_rps' => $request->status,
        ]);

        return $this->successResponse('Berhasil mengubah data dosen RPS');
    }

    public function update(Employee $employee, EmployeeRequest $request)
    {
        try {

            $request->merge([
                'employee_active_status_id' => $request->has('employee_active_status_id') && $request->employee_active_status_id != '' ? Hashids::decode($request->employee_active_status_id)[0] : null,
                'employee_status_id' => $request->has('employee_status_id') && $request->employee_status_id != '' ? Hashids::decode($request->employee_status_id)[0] : null,
                'religion_id' => $request->has('religion_id') && $request->religion_id != '' ? Hashids::decode($request->religion_id)[0] : null,
                'scientific_field_id' => $request->has('scientific_field_id') && $request->scientific_field_id != '' ? Hashids::decode($request->scientific_field_id)[0] : null,
                'university_id' => $request->has('university_id') && $request->university_id != '' ? Hashids::decode($request->university_id)[0] : null,
                'employee_type_id' => $request->has('employee_type_id') && $request->employee_type_id != '' ? Hashids::decode($request->employee_type_id)[0] : null,
                'region_id' => $request->has('region_id') && $request->region_id != '' ? Hashids::decode($request->region_id)[0] : null,
                'family_profession_id' => $request->has('family_profession_id') && $request->family_profession_id != '' ? Hashids::decode($request->family_profession_id)[0] : null,
            ]);

            $pictureName = null;

            if ($request->hasFile('photo_picture')) {
                $pictureName = uniqid('pegawai-') . '.' . $request->file('photo_picture')->getClientOriginalExtension();
                $request->file('photo_picture')->move(public_path(self::EMPLOYEE_PICTURE_PATH), $pictureName);

                if ($employee->picture != 'default_employee_pic.jpg' && File::exists(public_path(self::EMPLOYEE_PICTURE_PATH))) {
                    File::delete(public_path(self::EMPLOYEE_PICTURE_PATH) . $employee->picture);
                }

                $request->merge(['picture' => $pictureName]);
            }

            $employee->update($request->only([
                'nip', 'nidn', 'nidk', 'nupn', 'nuptk', 'nbm', 'name', 'gender', 'birthplace', 'birthdate', 'phone_number', 'house_phone_number', 'personal_email', 'campus_email', 'front_title', 'back_title', 'street', 'neighbourhood', 'hamlet', 'village_lev_1', 'village_lev_2', 'address', 'postal_code', 'tax_number', 'tax_name', 'mother_name', 'cpns_number', 'cpns_date', 'appointment_number', 'appointment_end_date', 'family_name', 'family_nip', 'marital_status', 'employee_active_status_id', 'employee_status_id', 'employee_type_id', 'university_id', 'scientific_field_id', 'religion_id', 'country_id', 'family_profession_id', 'region_id', 'picture'
            ]));

            return $this->successResponse('Berhasil mengubah data pegawai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show($employee)
    {
        try {
            $employee = Employee::where('id', $employee)->with(['employeeStatus', 'employeeActiveStatus', 'employeeType', 'university', 'scientificField', 'religion', 'familyProfession', 'region', 'country'])->first();

            $employee->hashed_religion_id = $employee->religion_id ? Hashids::encode($employee->religion_id) : '';
            $employee->hashed_scientific_field_id = $employee->scientific_field_id ? Hashids::encode($employee->scientific_field_id) : '';
            $employee->hashed_university_id = $employee->university_id ? Hashids::encode($employee->university_id) : '';
            $employee->hashed_employee_active_status_id = $employee->employee_active_status_id ? Hashids::encode($employee->employee_active_status_id) : '';
            $employee->hashed_employee_status_id = $employee->employee_status_id ? Hashids::encode($employee->employee_status_id) : '';
            $employee->hashed_employee_type_id = $employee->employee_type_id ? Hashids::encode($employee->employee_type_id) : '';
            $employee->hashed_region_id = $employee->region_id ? Hashids::encode($employee->region_id) : '';
            $employee->hashed_family_profession_id = $employee->family_profession_id ? Hashids::encode($employee->family_profession_id) : '';
            $employee->full_picture_path = self::EMPLOYEE_PICTURE_PATH . $employee->picture;

            return $this->successResponse(null, [
                'employee' => $employee,
            ]);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Employee $employee)
    {
        try {

            $imageName = $employee->picture;

            $employee->delete();

            if ($imageName != 'default_employee_pic.jpg' && File::exists(public_path(self::EMPLOYEE_PICTURE_PATH))) {
                File::delete(public_path(self::EMPLOYEE_PICTURE_PATH) . $employee->picture);
            }

            return $this->successResponse('Berhasil menghapus data pegawai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function teachingLecturer(Employee $employee, Request $request)
    {
        $query = TeachingLecturer::where(['employee_id' => $employee->id])
            ->with(['collegeClass.course', 'collegeClass.studyProgram.educationLevel', 'collegeClass.academicPeriod']);

        if (!empty($request->academic_period_id) and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        return DataTables::of($query->get())->make();
    }

    public function activity(Employee $employee, Request $request)
    {
        $query = StudentActivityMember::whereHas('studentActivity', function ($data) use ($employee, $request) {
            $data->whereHas('studentActivitySupervisor', function ($q) use ($employee, $request) {
                $q->where('employee_id', $employee->id);
                $q->where('role_type', $request->is_examiner == 'true' ? '1' : '0');
            });
        })->with(['student', 'studentActivity.academicPeriod', 'studentActivity.studyProgram.educationLevel', 'studentActivity.studentActivityCategory']);

        if (!empty($request->academic_period_id) and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        return DataTables::of($query->get())->make();
    }

    public function print(Request $request) {
        try{
            $employeeStatus = '';
            $employeeActiveStatus = '';
            $employeeType = '';

            if ($request->employee_types_id == null) {
               $employeeType = 'SEMUA JENIS PEGAWAI PEGWAI';
            }else{
                $employeeType = EmployeeType::find($request->employee_types_id)->name;
            }
            if ($request->employee_statuses_id == null) {
               $employeeStatus = 'SEMUA JENIS STATUS PEGWAI';
            }else{
               $employeeStatus = 'SEMUA JENIS STATUS PEGWAI';
               $employeeStatus = EmployeeStatus::find($request->employee_statuses_id)->name;
            }
            if ($request->employee_sctive_statuses_id == null) {
                $employeeActiveStatus = 'SEMUA JENIS STATUS AKTIF PEGAWAI';
            }else{
                $employeeActiveStatusID = EmployeeActiveStatus::findByHashid($request->employee_sctive_statuses_id)->id;
                $employeeActiveStatus = EmployeeActiveStatus::find($employeeActiveStatusID)->name;
            }

            $header = [
                'status' => $employeeStatus,
                'type' => $employeeType,
                'ActiveStatus' => $employeeActiveStatus,
            ];

            $query = Employee::with(['religion', 'employeeActiveStatus', 'employeeType','employeeStatus']);

            if ($request->has('employee_types_id') && $request->employee_types_id != '' && $request->employee_types_id != 'all') {
                $query->whereHas('employeeType', function ($q) use ($request) {
                    $q->where('id', $request->employee_types_id);
                });
            }

            if ($request->has('employee_statuses_id') && $request->employee_statuses_id != '' && $request->employee_statuses_id != 'all') {
                    $query->where('employee_status_id', $request->employee_statuses_id);
            }

            if ($request->has('employee_sctive_statuses_id') && $request->employee_sctive_statuses_id != '' && $request->employee_sctive_statuses_id != 'all') {
                $employeeActiveStatusID = EmployeeActiveStatus::findByHashid($request->employee_sctive_statuses_id)->id;
                $query->whereHas('employeeActiveStatus', function ($q) use ($employeeActiveStatusID) {
                    $q->where('id', $employeeActiveStatusID);
                });
            }
            return view('print.employee' , [
                'title' => 'Daftar Dosen | Politeknik Negeri Banyuwangi',
                'data' => $query->get(),
                'universitasProfile' => UniversityProfile::first(),
                'header' => $header,
            ]);
        }catch(Exception $e){
            return abort(404);
        }
    }

    public function scheduleSemester(Employee $employee, Request $request)
    {
        $query = CollegeClass::where(['academic_period_id' => getActiveAcademicPeriod()->id])
            ->whereHas('teachingLecturer', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->with(['course', 'weeklySchedule.room', 'teachingLecturer.employee', 'weeklySchedule.day', 'studyProgram.educationLevel']);

        return DataTables::of($query->get())->make();
    }
}
