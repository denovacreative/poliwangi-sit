<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\EducationLevelRequest;
use App\Models\EducationLevel;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EducationLevelController extends Controller
{
    public function index()
    {
        return DataTables::of(EducationLevel::all())->make();
    }

    public function show(EducationLevel $educationLevel)
    {
        return $this->successResponse(null, compact('educationLevel'));
    }

    public function store(EducationLevelRequest $request)
    {
        try {
            EducationLevel::create($request->only(['code', 'name', 'name_en', 'number', 'is_college', 'is_postgraduate']));
            return $this->successResponse('Berhasil membuat data tingkat pendidikan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(EducationLevel $educationLevel, EducationLevelRequest $request)
    {
        try {
            $educationLevel->update($request->only(['code', 'name', 'name_en', 'number', 'is_college', 'is_postgraduate']));
            return $this->successResponse('Berhasil mengupdate data tingkat pendidikan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(EducationLevel $educationLevel)
    {
        try {
            $educationLevel->delete();
            return $this->successResponse('Berhasil menghapus data tingkat pendidikan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
