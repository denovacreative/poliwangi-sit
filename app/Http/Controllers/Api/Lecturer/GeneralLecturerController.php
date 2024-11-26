<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;

class GeneralLecturerController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (isset($request->id) && $request->id != null) {
                $lecturer = Employee::with(['employeeActiveStatus', 'religion', 'employeeType'])->where('id', $request->id)->first();
            } else {
                $lecturer = getInfoLogin()->userable;
            }
            $employee = $lecturer->front_title == null ? '' : $lecturer->front_title;
            $employee .= $lecturer->name;
            $employee .= $lecturer->back_title == null ? '' : ', ' . $lecturer->back_title;
            $data = [
                'nip' => $lecturer->nip == null ? '-' : $lecturer->nip,
                'nidn' => $lecturer->nidn == null ? '-' : $lecturer->nidn,
                'birthplace' => $lecturer->birthplace == null ? '-' : $lecturer->birthplace,
                'birthdate' => $lecturer->birthdate == null ? '-' : $lecturer->birthdate,
                'gender' => $lecturer->gender,
                'religion' => $lecturer->religion_id == null ? '-' : $lecturer->religion->name,
                'active_status' => $lecturer->employeeActiveStatus->name,
                'name' => $employee,
                'email' => $lecturer->personal_email,
                'type' => $lecturer->employee_type_id == null ? '-' : $lecturer->employeeType->name
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
