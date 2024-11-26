<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StudentActivityCategoryRequest;
use App\Models\StudentActivityCategory;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StudentActivityCategoryController extends Controller
{
    public function index()
    {
        return DataTables::of(StudentActivityCategory::all())->make();
    }

    public function show(StudentActivityCategory $studentActivityCategory)
    {
        return $this->successResponse(null, compact('studentActivityCategory'));
    }

    public function store(StudentActivityCategoryRequest $request)
    {
        try {
            StudentActivityCategory::create($request->only(['code', 'name', 'is_mbkm']));
            return $this->successResponse('Berhasil membuat data jenis kegiatan pendukung');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(StudentActivityCategory $studentActivityCategory, StudentActivityCategoryRequest $request)
    {
        try {
            $studentActivityCategory->update($request->only(['code', 'name', 'is_mbkm']));
            return $this->successResponse('Berhasil mengupdate data jenis kegiatan pendukung');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(StudentActivityCategory $studentActivityCategory)
    {
        try {
            $studentActivityCategory->delete();
            return $this->successResponse('Berhasil menghapus data jenis kegiatan pendukung');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
