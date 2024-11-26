<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UniversityProfile;
use DataTables;

class UniversityProfileController extends Controller
{

    public function index()
    {
        try {
            $data = UniversityProfile::first();
            $data->employee;
            $data->viceChancellor;
            $data->viceChancellor2;
            $data->viceChancellor3;

            return $this->successResponse(null, compact('data'));
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(UniversityProfile $universityProfile, Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'name_en' => 'required',
            'alias' => 'required',
            'phone_number' => 'required',
            'establishment_number' => 'required',
            'employee_id' => 'required',
            'vice_chancellor' => "required|not_in:$request->employee_id",
            'vice_chancellor_2' => "required|not_in:$request->employee_id,$request->vice_chancellor",
            'vice_chancellor_3' => "required|not_in:$request->employee_id,$request->vice_chancellor,$request->vice_chancellor_2",
        ]);

        try {

            $universityProfile->update($request->only(['code', 'name', 'name_en', 'alias', 'address', 'branch_unit', 'phone_number', 'acreditation', 'acreditation_number', 'establishment_number', 'employee_id', 'vice_chaceller', 'vice_chaceller_2', 'vice_chanceller_3']));

            return $this->successResponse('Berhasil memperbarui data');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

}
