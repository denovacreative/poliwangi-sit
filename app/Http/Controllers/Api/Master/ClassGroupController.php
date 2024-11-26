<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ClassGroupRequest;
use App\Models\ClassGroup;
use App\Models\StudyProgram;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class ClassGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassGroup::with(['academicYear', 'studyProgram.educationLevel']);
        if (!empty($request->academic_year_id) and $request->academic_year_id != 'all') {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if (!empty($request->study_program_id) and $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }
        return DataTables::of($query)->addColumn('study_program', function ($data) {
            return $data->studyProgram->educationLevel->code . ' - ' . $data->studyProgram->name;
        })->make();
    }

    public function store(ClassGroupRequest $request)
    {
        try {
            ClassGroup::create([
                'code' => $request->code,
                'name' => $request->name,
                'academic_year_id' => $request->academic_year,
                'study_program_id' => $request->study_program
            ]);
            return $this->successResponse('Berhasil membuat data grup kelas baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(ClassGroup $classGroup)
    {
        return $this->successResponse(null, compact('classGroup'));
    }

    public function destroy(ClassGroup $classGroup)
    {
        try {
            $classGroup->delete();
            return $this->successResponse('Berhasil menghapus data grup kelas');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(ClassGroup $classGroup, ClassGroupRequest $request)
    {
        try {
            $classGroup->update([
                'code' => $request->code,
                'name' => $request->name,
                'academic_year_id' => $request->academic_year,
                'study_program_id' => $request->study_program
            ]);
            return $this->successResponse('Berhasil mengupdate data grup kelas');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
