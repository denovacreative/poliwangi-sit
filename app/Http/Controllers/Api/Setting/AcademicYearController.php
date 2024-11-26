<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\AcademicYearRequest;
use App\Models\AcademicYear;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AcademicYearController extends Controller
{
    public function index()
    {
        return DataTables::of(AcademicYear::all())->make();
    }

    public function show(AcademicYear $academicYear)
    {
        return $this->successResponse(null, compact('academicYear'));
    }

    public function store(AcademicYearRequest $request)
    {
        try {
            AcademicYear::create(['id' => $request->code, 'name' => $request->name]);
            return $this->successResponse('Berhasil membuat data tahun ajaran');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(AcademicYear $academicYear, AcademicYearRequest $request)
    {
        try {
            $academicYear->update(['id' => $request->code, 'name' => $request->name]);
            return $this->successResponse('Berhasil mengupdate data tahun ajaran');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(AcademicYear $academicYear)
    {
        try {
            $academicYear->delete();
            return $this->successResponse('Berhasil menghapus data tahun ajaran');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
