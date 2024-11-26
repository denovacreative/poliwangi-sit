<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\AcademicActivityRequest;
use Illuminate\Http\Request;
use App\Models\AcademicActivity;
use DataTables;
use Exception;

class AcademicActivityController extends Controller
{
    public function index()
    {
        return DataTables::of(AcademicActivity::query())->make();
    }

    public function store(AcademicActivityRequest $request)
    {
        try {
            AcademicActivity::create($request->only(['name', 'color']));
            return $this->successResponse('Berhasil membuat data aktivitas akademik baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(AcademicActivity $academicActivity)
    {
        return $this->successResponse(null, compact('academicActivity'));
    }

    public function destroy(AcademicActivity $academicActivity)
    {
        try {
            $academicActivity->delete();
            return $this->successResponse('Berhasil menghapus data aktivitas akademik!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(AcademicActivity $academicActivity, AcademicActivityRequest $request)
    {
        try {
            $academicActivity->update($request->only([
                'name', 'color'
            ]));
            return $this->successResponse('Berhasil mengupdate data aktivitas akademik!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
