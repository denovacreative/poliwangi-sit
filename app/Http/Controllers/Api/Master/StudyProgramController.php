<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StudyProgramRequest;
use App\Models\StudyProgram;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class StudyProgramController extends Controller
{
    public function index()
    {
        $query = StudyProgram::with(['major', 'educationLevel', 'academicPeriod']);
        if (mappingAccess() != null) {
            $query->whereIn('id', mappingAccess());
        }
        return DataTables::of($query)->addColumn('major', function ($data) {
            return $data->major_id == null ? '-' : $data->major->name;
        })->make();
    }

    public function store(StudyProgramRequest $request)
    {
        try {
            StudyProgram::create([
                'id' => Uuid::uuid4(),
                'code' => $request->code,
                'name' => $request->name,
                'name_en' => $request->name_en,
                'alias' => $request->alias,
                'phone_number' => $request->phone_number,
                'faximile' => $request->faximile,
                'email' => $request->email,
                'website' => $request->website,
                'address' => $request->address,
                'establishment_date' => $request->establishment_date,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'is_active' => $request->active,
                'acreditation' => $request->acreditation,
                'acreditation_number' => $request->acreditation_number,
                'acreditation_date' => $request->acreditation_date,
                'title' => $request->title,
                'title_alias' => $request->title_alias,
                'title_en' => $request->title_en,
                'major_id' => $request->major,
                'education_level_id' => $request->education_level,
                'academic_period_id' => $request->academic_period,
                'employee_id' => $request->employee_id
            ]);
            return $this->successResponse('Berhasil membuat data program studi baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(StudyProgram $studyProgram)
    {
        return $this->successResponse(null, compact('studyProgram'));
    }

    public function destroy(StudyProgram $studyProgram)
    {
        try {
            $studyProgram->delete();
            return $this->successResponse('Berhasil menghapus data program studi');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(StudyProgram $studyProgram, StudyProgramRequest $request)
    {
        try {
            $studyProgram->update([
                'code' => $request->code,
                'name' => $request->name,
                'name_en' => $request->name_en,
                'alias' => $request->alias,
                'phone_number' => $request->phone_number,
                'faximile' => $request->faximile,
                'email' => $request->email,
                'website' => $request->website,
                'address' => $request->address,
                'establishment_date' => $request->establishment_date,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'is_active' => $request->active,
                'acreditation' => $request->acreditation,
                'acreditation_number' => $request->acreditation_number,
                'acreditation_date' => $request->acreditation_date,
                'title' => $request->title,
                'title_alias' => $request->title_alias,
                'title_en' => $request->title_en,
                'major_id' => $request->major,
                'education_level_id' => $request->education_level,
                'academic_period_id' => $request->academic_period,
                'employee_id' => $request->employee_id,
            ]);
            return $this->successResponse('Berhasil mengupdate data program studi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
