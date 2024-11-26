<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\LectureSytemRequest;
use App\Models\LectureSystem;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LectureSystemController extends Controller
{
    public function index()
    {
        return DataTables::of(LectureSystem::query())->make();
    }

    public function store(LectureSytemRequest $request)
    {
        try {
            LectureSystem::create($request->only(['name']));
            return $this->successResponse('Berhasil membuat data sistem kuliah baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(LectureSystem $lectureSystem)
    {
        return $this->successResponse(null, compact('lectureSystem'));
    }

    public function destroy(LectureSystem $lectureSystem)
    {
        try {
            $lectureSystem->delete();
            return $this->successResponse('Berhasil menghapus data sistem kuliah');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(LectureSystem $lectureSystem, LectureSytemRequest $request)
    {
        try {
            $lectureSystem->update($request->only(['name']));
            return $this->successResponse('Berhasil mengupdate data sistem kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
