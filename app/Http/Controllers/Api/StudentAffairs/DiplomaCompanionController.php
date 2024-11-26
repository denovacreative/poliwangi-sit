<?php

namespace App\Http\Controllers\Api\StudentAffairs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAffairs\DiplomaCompanionRequest;
use App\Models\DiplomaCompanion;
use App\Models\StudyProgram;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DiplomaCompanionController extends Controller
{
    public function index()
    {
        $query = DiplomaCompanion::with(['studyProgram.educationLevel']);
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }
        return DataTables::of($query)->addColumn('study_program_name', function ($data) {
            return $data->studyProgram->educationLevel->code . ' - ' . $data->studyProgram->name;
        })->addColumn('education_level_name', function ($data) {
            return $data->studyProgram->educationLevel->code . ' - ' . $data->studyProgram->educationLevel->name;
        })->make();
    }

    public function show(DiplomaCompanion $diplomaCompanion)
    {
        $diplomaCompanion->studyProgram->educationLevel;
        return $this->successResponse(null, compact('diplomaCompanion'));
    }

    public function store(DiplomaCompanionRequest $request)
    {
        try {
            $checkExistingData = DiplomaCompanion::where(['study_program_id' => $request->study_program])->first();
            if ($checkExistingData) {
                return $this->errorResponse(500, 'Terdapat duplikasi data!');
            }
            DiplomaCompanion::create([
                'study_program_id' => $request->study_program,
                'education_level_id' => $request->education_level,
                'terms_acceptance' => $request->terms_acceptance,
                'terms_acceptance_en' => $request->terms_acceptance_en,
                'study' => $request->study,
                'type_education' => $request->type_education,
                'type_education_en' => $request->type_education_en,
                'next_type_education' => $request->next_type_education,
                'next_type_education_en' => $request->next_type_education_en,
                'kkni_level' => $request->kkni_level,
                'profession_status' => $request->profession_status,
                'instruction_language' => $request->instruction_language,
                'instruction_language_en' => $request->instruction_language_en,
                'introduction' => $request->introduction,
                'introduction_en' => $request->introduction_en,
                'kkni_info' => $request->kkni_info,
                'kkni_info_en' => $request->kkni_info_en,
                'work_ability' => $request->work_ability,
                'work_ability_en' => $request->work_ability_en,
                'mastery_of_knowledge' => $request->mastery_of_knowledge,
                'mastery_of_knowledge_en' => $request->mastery_of_knowledge_en,
                'special_attitude' => $request->special_attitude,
                'special_attitude_en' => $request->special_attitude_en,
            ]);
            return $this->successResponse('Berhasil membuat setting informasi prodi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(DiplomaCompanion $diplomaCompanion, DiplomaCompanionRequest $request)
    {
        try {
            if ($diplomaCompanion->study_program_id != $request->study_program) {
                $checkExistingData = DiplomaCompanion::where(['study_program_id' => $request->study_program])->first();
                if ($checkExistingData) {
                    return $this->errorResponse(500, 'Terdapat duplikasi data!');
                }
            }
            $diplomaCompanion->update([
                'study_program_id' => $request->study_program,
                'education_level_id' => $request->education_level,
                'terms_acceptance' => $request->terms_acceptance,
                'terms_acceptance_en' => $request->terms_acceptance_en,
                'study' => $request->study,
                'type_education' => $request->type_education,
                'type_education_en' => $request->type_education_en,
                'next_type_education' => $request->next_type_education,
                'next_type_education_en' => $request->next_type_education_en,
                'kkni_level' => $request->kkni_level,
                'profession_status' => $request->profession_status,
                'instruction_language' => $request->instruction_language,
                'instruction_language_en' => $request->instruction_language_en,
                'introduction' => $request->introduction,
                'introduction_en' => $request->introduction_en,
                'kkni_info' => $request->kkni_info,
                'kkni_info_en' => $request->kkni_info_en,
                'work_ability' => $request->work_ability,
                'work_ability_en' => $request->work_ability_en,
                'mastery_of_knowledge' => $request->mastery_of_knowledge,
                'mastery_of_knowledge_en' => $request->mastery_of_knowledge_en,
                'special_attitude' => $request->special_attitude,
                'special_attitude_en' => $request->special_attitude_en,
            ]);
            return $this->successResponse('Berhasil mengupdate setting informasi prodi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
