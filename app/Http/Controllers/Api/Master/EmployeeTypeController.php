<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\EmployeeTypeRequest;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class EmployeeTypeController extends Controller
{
    public function index()
    {
        return DataTables::of(EmployeeType::query())->make();
    }

    public function store(EmployeeTypeRequest $request)
    {
        try {
            EmployeeType::create($request->only(['code', 'name']));
            return $this->successResponse('Berhasil membuat data jenis pegawai baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(EmployeeType $employeeType)
    {
        return $this->successResponse(null, compact('employeeType'));
    }

    public function destroy(EmployeeType $employeeType)
    {
        try {
            $employeeType->delete();
            return $this->successResponse('Berhasil menghapus data jenis pegawai!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(EmployeeType $employeeType, EmployeeTypeRequest $request)
    {
        try {
            $employeeType->update($request->only(['name', 'code']));
            return $this->successResponse('Berhasil mengupdate data jenis pegawai!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
