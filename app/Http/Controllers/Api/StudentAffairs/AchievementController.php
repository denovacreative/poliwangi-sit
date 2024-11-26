<?php

namespace App\Http\Controllers\Api\StudentAffairs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAffairs\AchievementRequest;
use App\Models\AcademicPeriod;
use App\Models\Achievement;
use App\Models\AchievementGroup;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Ramsey\Uuid\Uuid;
use File;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        //
        $query = Achievement::with(['student','achievementType', 'achievementGroup', 'academicPeriod']);
        if (!is_null($request->academic_period) and $request->academic_period != '' and $request->academic_period != 'all') {
            $query->where('academic_period_id', $request->academic_period);
        }

        if (!is_null($request->achievement_group) and $request->achievement_group != '' and $request->achievement_group != 'all') {
            $query->where('achievement_group_id', $request->achievement_group);
        }
        
        if (!is_null($request->event_type) and $request->event_type != '' and $request->event_type != 'all') {
            $query->where('event_type', $request->event_type);
        }
        return DataTables::of($query)->make();
    }

    public function store(AchievementRequest $request)
    {
        //
        try{

            $achievement_group = AchievementGroup::where('id', $request->achievement_group)->first();
            $achievement_type_id = $achievement_group->achievement_type_id;
            $academic_period = AcademicPeriod::where('id', $request->academic_period)->first();
            $getYearPeriod = $academic_period->academic_year_id;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = 'Achievement_' . time() . rand(0, 99999999999) . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/documents/achievement'), $fileName);
            } else {
                $fileName = 'default.png';
            }
            $request->merge([
                'attachment' => $fileName,
            ]);

            $uuid = Uuid::uuid4();

            Achievement::create([
                'id' => (string) $uuid,
                'name' => $request->name,
                'name_en' => $request->name_en,
                'year' => $getYearPeriod,
                'event_type' => $request->event_type,
                'rating' => $request->rate,
                'point' => $achievement_group->point,
                'position' => $request->position,
                'location' => $request->location,
                'organizer' => $request->organizer,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'attachment' => $request->attachment,
                'is_valid' => $request->is_valid,
                'is_show_skpi' => $request->is_show_skpi,
                'achievement_group_id' => $request->achievement_group,
                'achievement_type_id' => $achievement_type_id,
                'achievement_level_id' => $request->achievement_level,
                'academic_period_id' => $request->academic_period,
                'student_id' => $request->student,
                'validator_id' => Auth::user()->id,
                'validation_date' => date('Y-m-d'),
            ]);

            return $this->successResponse('Data berhasil ditambahkan');
        }catch(Exception $e){

            return $this->exceptionResponse($e);

        }
    }

    public function show(Achievement $achievement)
    {
        //
        return $this->successResponse(null, compact('achievement'));
    }

    public function update(AchievementRequest $request, Achievement $achievement)
    {
        //
        try{

            $achievement_group = AchievementGroup::where('id', $request->achievement_group)->first();
            $achievement_type_id = $achievement_group->achievement_type_id;
            $academic_period = AcademicPeriod::where('id', $request->academic_period)->first();
            $getYearPeriod = $academic_period->academic_year_id;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = 'Achievement_' . time() . rand(0, 99999999999) . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/documents/achievement'), $fileName);
            } else {
                $fileName = $achievement->attachment;
            }
            $request->merge([
                'attachment' => $fileName,
            ]);

            $achievement->update([
                'name' => $request->name,
                'name_en' => $request->name_en,
                'year' => $getYearPeriod,
                'event_type' => $request->event_type,
                'rating' => $request->rate,
                'point' => $achievement_group->point,
                'position' => $request->position,
                'location' => $request->location,
                'organizer' => $request->organizer,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'attachment' => $request->attachment,
                'is_valid' => $request->is_valid,
                'is_show_skpi' => $request->is_show_skpi,
                'achievement_group_id' => $request->achievement_group,
                'achievement_type_id' => $achievement_type_id,
                'achievement_level_id' => $request->achievement_level,
                'academic_period_id' => $request->academic_period,
                'student_id' => $request->student,
            ]);

            return $this->successResponse('Data berhasil diupdate');

        }catch(Exception $e){
            return $this->exceptionResponse($e);

        }
    }

    public function destroy(Achievement $achievement)
    {
        //
        try{

            if (file_exists(public_path('storage/documents/achievement' . $achievement->attachment))) {
                if ($achievement->attachment != 'default.png') {
                    File::delete(public_path('storage/documents/achievement' . $achievement->attachment));
                }
            }

            $achievement->delete();

            return $this->successResponse('Data berhasil dihapus');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
