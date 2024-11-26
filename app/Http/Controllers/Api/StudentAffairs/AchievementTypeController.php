<?php

namespace App\Http\Controllers\Api\StudentAffairs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAffairs\AchievementTypeRequest;
use App\Models\AchievementType;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AchievementTypeController extends Controller
{
    public function index()
    {
        return DataTables::of(AchievementType::query())->make();
    }

    public function store(AchievementTypeRequest $request)
    {
        try {
            AchievementType::create($request->only(['code', 'name']));
            return $this->successResponse('Berhasil menambahkan data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(AchievementType $achievementType)
    {
        try {
            return $this->successResponse(null, compact('achievementType'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(AchievementTypeRequest $request, AchievementType $achievementType)
    {
        try {
            $achievementType->update([
                'code' => $request->code,
                'name' => $request->name
            ]);
            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(AchievementType $achievementType)
    {
        try {
            $achievementType->delete();
            return $this->successResponse('Berhasil menghapus data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
