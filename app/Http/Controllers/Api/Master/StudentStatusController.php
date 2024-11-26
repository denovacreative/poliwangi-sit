<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StudentStatusRequest;
use App\Models\StudentStatus;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class StudentStatusController extends Controller
{
    public function index()
    {
        return DataTables::of(StudentStatus::query())->make();
    }

    public function store(StudentStatusRequest $request)
    {
        try {
            StudentStatus::create([
                'id' => $request->code,
                'name' => $request->name,
                'is_submited' => isset($request->submit) && $request->submit == 'on' ? true : false,
                'is_active' => $request->status,
                'is_college' => isset($request->college) && $request->college == 'on' ? true : false,
                'is_default' => false
            ]);
            return $this->successResponse('Berhasil membuat data status mahasiswa baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(StudentStatus $studentStatus)
    {
        return $this->successResponse(null, compact('studentStatus'));
    }

    public function destroy(StudentStatus $studentStatus)
    {
        try {
            $studentStatus->delete();
            return $this->successResponse('Berhasil menghapus data status mahasiswa');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(StudentStatus $studentStatus, StudentStatusRequest $request)
    {
        try {
            $studentStatus->update([
                'id' => $request->code,
                'name' => $request->name,
                'is_submited' => isset($request->submit) && $request->submit == 'on' ? true : false,
                'is_active' => $request->status,
                'is_college' => isset($request->college) && $request->college == 'on' ? true : false,
                'is_default' => false
            ]);
            return $this->successResponse('Berhasil mengupdate data status mahasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
