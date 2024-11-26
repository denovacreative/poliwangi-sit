<?php

namespace App\Http\Controllers\Api\StudentAffairs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAffairs\ScholarshipTypeRequest;
use App\Models\ScholarshipType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ScholarshipTypeController extends Controller
{
    public function index()
    {
        return DataTables::of(ScholarshipType::all())->make();
    }

    public function show(ScholarshipType $scholarshipType)
    {
        return $this->successResponse(null, compact('scholarshipType'));
    }

    public function store(ScholarshipTypeRequest $request)
    {
        try {
            ScholarshipType::create($request->only(['name']));
            return $this->successResponse('Berhasil membuat data jenis beasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(ScholarshipType $scholarshipType, ScholarshipTypeRequest $request)
    {
        try {
            $scholarshipType->update($request->only(['name']));
            return $this->successResponse('Berhasil mengupdate data jenis beasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(ScholarshipType $scholarshipType)
    {
        try {
            $scholarshipType->delete();
            return $this->successResponse('Berhasil menghapus data jenis beasiswa');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
