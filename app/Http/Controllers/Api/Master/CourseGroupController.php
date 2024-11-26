<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CourseGroupRequest;
use App\Models\CourseGroup;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourseGroupController extends Controller
{
    public function index()
    {
        return DataTables::of(CourseGroup::all())->make();
    }

    public function show(CourseGroup $courseGroup)
    {
        return $this->successResponse(null, compact('courseGroup'));
    }

    public function store(CourseGroupRequest $request)
    {
        try {
            CourseGroup::create($request->only(['code', 'name']));
            return $this->successResponse('Berhasil membuat data kelompok mata kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(CourseGroup $courseGroup, CourseGroupRequest $request)
    {
        try {
            $courseGroup->update($request->only(['code', 'name']));
            return $this->successResponse('Berhasil mengupdate data kelompok mata kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(CourseGroup $courseGroup)
    {
        try {
            $courseGroup->delete();
            return $this->successResponse('Berhasil menghapus data kelompok mata kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
