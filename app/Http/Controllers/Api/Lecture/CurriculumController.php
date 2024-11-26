<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CurriculumRequest;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use DataTables;
use Ramsey\Uuid\Uuid;
use Vinkla\Hashids\Facades\Hashids;
use Exception;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        try {
            $curriculum = Curriculum::query();
            if ($request->has('study_program_id') && $request->study_program_id != '') {
                $curriculum->where('study_program_id', $request->study_program_id);
            }
            if ($request->has('academic_period_id') && $request->academic_period_id != '') {
                $curriculum->where('academic_period_id', Hashids::decode($request->academic_period_id)[0]);
            }
            $curriculum = $curriculum->with(['studyProgram.educationLevel.educationLevelSetting', 'academicPeriod', 'courseCurriculum'])->get()->map(function ($x) {
                return $this->countUsedCredits($x);
            });
            return $this->successResponse('Berhasil mendapatkan data kurikulum', compact('curriculum'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function store(CurriculumRequest $request)
    {
        $hashedFields = ['academic_period_id'];

        foreach ($hashedFields as $hf) $request->merge([$hf => $request->has($hf) ? Hashids::decode($request[$hf])[0] : null]);

        $request->merge([
            'id' => Uuid::uuid4(),
            'credit_total' => ($request->mandatory_credit ?? 0) + ($request->choice_credit ?? 0),
            'mandatory_credit' => $request->mandatory_credit,
            'choice_credit' => $request->choice_credit,
        ]);

        Curriculum::create($request->only([
            'id', 'name', 'study_program_id', 'academic_period_id', 'credit_total', 'mandatory_credit', 'choice_credit'
        ]));

        return $this->successResponse('Berhasil membuat data kurikulum');
    }

    public function show(Curriculum $curriculum)
    {
        $curriculum->academic_period_hashid = $curriculum->academic_period_id && $curriculum->academic_period_id != '' ? Hashids::encode($curriculum->academic_period_id) : '';
        return $this->successResponse('Berhasil mengambil data kurikulum', compact('curriculum'));
    }

    public function update(Curriculum $curriculum, CurriculumRequest $request)
    {
        $hashedFields = ['academic_period_id'];

        $request->merge(['credit_total' => ($request->mandatory_credit ?? 0) + ($request->choice_credit ?? 0)]);

        foreach ($hashedFields as $hf) $request->merge([$hf => $request->has($hf) ? Hashids::decode($request[$hf])[0] : null]);

        $curriculum->update($request->only([
            'name', 'academic_period_id', 'mandatory_credit', 'choice_credit'
        ]));

        return $this->successResponse('Berhasil mengubah data kurikulum');
    }

    public function destroy(Curriculum $curriculum)
    {
        try {
            $curriculum->delete();
            return $this->successResponse('Berhasil menghapus data kurikulum');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getCreditCount(Curriculum $curriculum)
    {
        return $this->countUsedCredits($curriculum);
    }

    private function countUsedCredits(Curriculum $curriculum)
    {
        $curriculum->used_mandatory_credit = $curriculum->courseCurriculum->filter(function ($y) {
            return $y->is_mandatory;
        })->pluck('credit_total')->sum();
        $curriculum->used_choice_credit = $curriculum->courseCurriculum->filter(function ($y) {
            return !$y->is_mandatory;
        })->pluck('credit_total')->sum();

        return $curriculum;
    }
}
