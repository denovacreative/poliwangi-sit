<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\StudyProgram;
use App\Models\StudyProgramSetting;
use Illuminate\Http\Request;

class StudyProgramSettingController extends Controller
{
    public function index(Request $request)
    {
        $studyPrograms = StudyProgram::with(['studyProgramSetting' => function($q) {
            $activeAcademicPeriod = getActiveAcademicPeriod(true);
            return $q->where('academic_period_id', $activeAcademicPeriod->id);
        }]);

        if ($request->has('major_id') && $request->major_id != null) {
            $studyPrograms->where('major_id', $request->major_id);
        }

        return response()->json([
            'study_programs' => $studyPrograms->get()
        ]);
    }

    public function show($studyProgramId)
    {
        $usedAcademicPeriod = getActiveAcademicPeriod(true);
        $studyProgramSetting = StudyProgramSetting::where('study_program_id', $studyProgramId)->where('academic_period_id', $usedAcademicPeriod->id)->first();

        if ($studyProgramSetting == null) return $this->errorResponse('Data setting prodi tidak ditemukan');

        return $this->successResponse('Berhasil mendapatkan data setting study program', [
            'studyProgramSetting' => $studyProgramSetting
        ]);
    }

    public function update(Request $request)
    {
        $usedAcademicPeriod = getActiveAcademicPeriod(true);
        $spSetting = StudyProgramSetting::where('study_program_id', $request->studyProgramId)->where('academic_period_id', $usedAcademicPeriod->id);

        if ($spSetting->count() <= 0) {
            $spSetting = StudyProgramSetting::create([
                'study_program_id' => $request->studyProgramId,
                'academic_period_id' => $usedAcademicPeriod->id,
                'is_guardianship' => false,
                'is_khs' => false,
                'is_krs' => false,
                'is_score' => false,
                'is_remedial_score' => false,
                'is_update_biodata' => false,
                'is_questionnaire' => false,
                'is_lecture_generate' => false,
                'number_of_meeting' => null,
            ]);
        }

        $d = $spSetting->update([
            $request->key => $request->value,
        ]);

        return $this->successResponse('Berhasil mengupdate setting prodi', [
            'd' => $request->value,
        ]);
    }

    public function updateDetail(Request $request, $studyProgramId)
    {
        $usedAcademicPeriod = getActiveAcademicPeriod(true);
        $data = StudyProgramSetting::where('study_program_id', $studyProgramId)->where('academic_period_id', $usedAcademicPeriod->id)->first();

        $data->update([
            $request->key => $request->value
        ]);

        return $this->successResponse('Berhasil meng-update detail setting prodi');
    }
}
