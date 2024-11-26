<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\ThesisRequirementRequest;
use App\Models\ThesisRequirement;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ThesisRequirementController extends Controller
{
    public function index(Request $request)
    {
        $query = ThesisRequirement::with('thesisStage');
        if (!empty($request->thesis_stage_id) and $request->thesis_stage_id != 'all') {
            $query->where('thesis_stage_id', $request->thesis_stage_id);
        }

        if (!empty($request->thesis_type) and $request->thesis_type != 'all') {
            $query->where('thesis_type', $request->thesis_type);
        }
        return DataTables::of($query)->addColumn('thesis_stage', function ($data) {
            return $data->thesisStage->name;
        })->make();
    }

    public function store(ThesisRequirementRequest $request)
    {
        try {
            $request->merge([
                'thesis_stage_id' => $request->thesis_stage,
                'is_active' => isset($request->is_active) && $request->is_active == 'on' ? true : false,
                'is_upload' => isset($request->is_upload) && $request->is_upload == 'on' ? true : false
            ]);
            ThesisRequirement::create($request->only(['name', 'is_active', 'is_upload', 'description', 'thesis_type', 'thesis_stage_id']));
            return $this->successResponse('Berhasil menambahkan data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(ThesisRequirement $thesisRequirement)
    {
        try {
            return $this->successResponse(null, compact('thesisRequirement'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(ThesisRequirementRequest $request, ThesisRequirement $thesisRequirement)
    {
        try {
            $request->merge([
                'thesis_stage_id' => $request->thesis_stage,
                'is_active' => isset($request->is_active) && $request->is_active == 'on' ? true : false,
                'is_upload' => isset($request->is_upload) && $request->is_upload == 'on' ? true : false
            ]);
            $thesisRequirement->update($request->only(['name', 'is_active', 'is_upload', 'description', 'thesis_type', 'thesis_stage_id']));
            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(ThesisRequirement $thesisRequirement)
    {
        try {
            $thesisRequirement->delete();
            return $this->successResponse('Berhasil menghapus data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
