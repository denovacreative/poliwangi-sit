<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducationLevelSetting;
use App\Http\Requests\Master\EducationLevelSettingRequest;
use DataTables;
use Exception;

class EducationLevelSettingController extends Controller
{

    public function index()
    {
        return DataTables::of(EducationLevelSetting::with(['educationLevel']))->make();
    }

    public function store(EducationLevelSettingRequest $request)
    {
        try {
            EducationLevelSetting::create($request->only(['education_level_id', 'study', 'max_leave', 'max_study']));
            return $this->successResponse('Berhasil menambahkan data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(EducationLevelSetting $educationLevelSetting)
    {
        return $this->successResponse(null, compact('educationLevelSetting'));
    }

    public function update(EducationLevelSettingRequest $request, EducationLevelSetting $educationLevelSetting)
    {
        try {
            $educationLevelSetting->update($request->only(['education_level_id', 'study', 'max_leave', 'max_study']));
            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(EducationLevelSetting $educationLevelSetting)
    {
        try {
            $educationLevelSetting->delete();
            return $this->successResponse('Berhasil menghapus data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
