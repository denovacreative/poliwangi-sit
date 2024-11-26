<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Master\MajorRequest;
use App\Models\Major;
use DataTables;
use Exception;

class MajorController extends Controller
{

    public function index()
    {
        $query = Major::query();
        if (mappingAccess() != null) {
            $query->whereHas('studyProgram', function ($q) {
                $q->whereIn('id', mappingAccess());
            });
        }
        return DataTables::of($query)->make();
    }

    public function store(MajorRequest $request)
    {
        try {
            Major::create($request->only(['name', 'name_en', 'alias', 'phone_number', 'faximile', 'email', 'website', 'address', 'establishment_date', 'decree_number', 'decree_date', 'is_active', 'employee_id']));

            return $this->successResponse('Data berhasil ditambahkan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(Major $major)
    {
        try {
            return $this->successResponse(null, compact('major'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(MajorRequest $request, Major $major)
    {
        try {
            $major->update($request->only(['name', 'name_en', 'alias', 'phone_number', 'faximile', 'email', 'website', 'address', 'establishment_date', 'decree_number', 'decree_date', 'is_active', 'employee_id']));

            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Major $major)
    {
        try {
            $major->delete();

            return $this->successResponse('Berhasil menghapus data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
