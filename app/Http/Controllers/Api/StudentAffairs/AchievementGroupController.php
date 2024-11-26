<?php

namespace App\Http\Controllers\Api\StudentAffairs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAffairs\AchievementGroupRequest;
use App\Models\AchievementGroup;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AchievementGroupController extends Controller
{
    public function index()
    {
        return DataTables::of(AchievementGroup::with(['achievementField', 'achievementType']))->addColumn('achievement_type_name', function ($data) {
            return $data->achievementType->name;
        })->addColumn('achievement_field_name', function ($data) {
            return $data->achievementField->name;
        })->make();
    }

    public function show(AchievementGroup $achievementGroup)
    {
        return $this->successResponse(null, compact('achievementGroup'));
    }
    public function updateStatus(AchievementGroup $achievementGroup)
    {
        try {
            $achievementGroup->update(['is_active' => !$achievementGroup->is_active]);
            return $this->successResponse('Berhasil mengubah status kelompok prestasi');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function store(AchievementGroupRequest $request)
    {
        try {
            $request->merge(['achievement_field_id' => $request->achievement_field, 'achievement_type_id' => $request->achievement_type]);
            AchievementGroup::create($request->only(['name', 'point', 'achievement_field_id', 'achievement_type_id', 'is_active']));
            return $this->successResponse('Berhasil membuat data kelompok prestasi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(AchievementGroup $achievementGroup, AchievementGroupRequest $request)
    {
        try {
            $request->merge(['achievement_field_id' => $request->achievement_field, 'achievement_type_id' => $request->achievement_type]);
            $achievementGroup->update($request->only(['name', 'point', 'achievement_field_id', 'achievement_type_id', 'is_active']));
            return $this->successResponse('Berhasil mengupdate data kelompok prestasi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(AchievementGroup $achievementGroup)
    {
        try {
            $achievementGroup->delete();
            return $this->successResponse('Berhasil menghapus data kelompok prestasi');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
