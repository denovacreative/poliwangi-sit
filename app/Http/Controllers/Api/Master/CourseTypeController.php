<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseType;
use App\Http\Requests\Master\CourseTypeRequest;
use DataTables;

class CourseTypeController extends Controller
{
    
    public function index() 
    {
        return DataTables::of(CourseType::query())->make();
    }

    public function store(CourseTypeRequest $request)
    {
        try {
            CourseType::create($request->only(['id', 'name']));

            return $this->successResponse('Berhasil menambahkan data');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(CourseType $courseType)
    {
        return $this->successResponse(null, compact('courseType'));
    }

    public function update(CourseType $courseType, CourseTypeRequest $request)
    {
        try {
            $courseType->update($request->only('name'));

            return $this->successResponse('Berhasil memperbarui data');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(CourseType $courseType)
    {
        try {
            $courseType->delete();

            return $this->successResponse('Berhasil menghapus data');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

}
