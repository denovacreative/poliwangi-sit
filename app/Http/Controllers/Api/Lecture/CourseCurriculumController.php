<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CourseCurriculumRequest;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use Exception;
use Ramsey\Uuid\Uuid;
use Vinkla\Hashids\Facades\Hashids;
use DataTables;
use Illuminate\Support\Facades\DB;

class CourseCurriculumController extends Controller
{
    public function index(Request $request)
    {
        try {
            $courseCurriculums = CourseCurriculum::with(['course', 'curriculum.studyProgram.educationLevel']);
            if ($request->has('curriculum_id') && $request->curriculum_id != '') {
                $courseCurriculums->where('curriculum_id', $request->curriculum_id);
            }
            return DataTables::of($courseCurriculums)->make();
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function store(CourseCurriculumRequest $request)
    {

        DB::beginTransaction();

        $course = Course::find($request->course_id);
        $curriculum = Curriculum::find($request->curriculum_id);

        $request->merge([
            'id' => Uuid::uuid4(),
            'credit_meeting' => $course->credit_meeting ?? 0,
            'credit_practicum' => $course->credit_practicum ?? 0,
            'credit_practice' => $course->credit_practice ?? 0,
            'credit_simulation' => $course->credit_simulation ?? 0,
        ]);

        $currentExistedCreditsCount = $curriculum->courseCurriculum()->where('is_mandatory', $request->is_mandatory == 'true')->sum('credit_total');
        $requestedCourseCurriculumCreditTotal = $request->credit_meeting + $request->credit_practicum + $request->credit_practice + $request->credit_simulation;

        if (($requestedCourseCurriculumCreditTotal + $currentExistedCreditsCount) > $curriculum[$request->is_mandatory == 'true' ? 'mandatory_credit' : 'choice_credit']) {
            throw new Exception('SKS ' . ($request->is_mandatory == 'true' ? 'wajib' : 'pilihan') . ' melebihi batas SKS kurikulum');
        }

        $request->merge([
            'credit_total' => $requestedCourseCurriculumCreditTotal,
            'is_mandatory' => $request->is_mandatory == 'true' ? true : false,
        ]);

        CourseCurriculum::create($request->only([
            'id', 'semester', 'credit_meeting', 'credit_practicum', 'credit_practice', 'credit_simulation', 'credit_total', 'course_id', 'curriculum_id', 'is_mandatory'
        ]));

        DB::commit();

        return $this->successResponse('Berhasil menambah mata kuliah di kurikulum ini');
    }

    public function show(CourseCurriculum $courseCurriculum)
    {
        return $this->successResponse('Berhasil mengambil data kurikulum', compact('courseCurriculum'));
    }

    public function update(CourseCurriculum $courseCurriculum, CourseCurriculumRequest $request)
    {
        DB::beginTransaction();

        $request->merge([
            'is_mandatory' => $request->is_mandatory == 'true' ? true : false,
        ]);

        if ($request->is_mandatory != $courseCurriculum->is_mandatory) {
            if (!$courseCurriculum->is_mandatory && $request->is_mandatory) {
                $courseCurriculum->curriculum->mandatory_credit += $courseCurriculum->credit_total;
                $courseCurriculum->curriculum->choice_credit -= $courseCurriculum->credit_total;
            } else {
                $courseCurriculum->curriculum->mandatory_credit -= $courseCurriculum->credit_total;
                $courseCurriculum->curriculum->choice_credit += $courseCurriculum->credit_total;
            }
            $courseCurriculum->curriculum->save();
        }

        $courseCurriculum->update($request->only([
            'course_id', 'semester', 'is_mandatory'
        ]));

        DB::commit();

        return $this->successResponse('Berhasil mengubah mata kuliah data kurikulum');
    }

    public function destroy(CourseCurriculum $courseCurriculum)
    {
        try {
            DB::beginTransaction();

            $courseCurriculum->curriculum[$courseCurriculum->is_mandatory ? 'mandatory_credit' : 'choice_credit'] -= $courseCurriculum->credit_total;
            $courseCurriculum->curriculum->credit_total = $courseCurriculum->curriculum->mandatory_credit + $courseCurriculum->curriculum->choice_credit;
            $courseCurriculum->curriculum->save();
            $courseCurriculum->delete();

            DB::commit();
            return $this->successResponse('Berhasil menghapus mata kuliah kurikulum');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
