<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\ScoreScaleRequest;
use App\Models\ScoreScale;
use App\Models\StudyProgram;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Ramsey\Uuid\Uuid;

class ScoreScaleController extends Controller
{
    public function index(Request $request)
    {
        $query = ScoreScale::query();
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }

        if (!empty($request->study_program_id) and $request->study_program_id != '' and $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        return DataTables::of($query->with(['studyProgram.educationLevel']))->make();
    }

    public function show(ScoreScale $scoreScale)
    {
        return $this->successResponse(null, compact('scoreScale'));
    }

    public function store(ScoreScaleRequest $request)
    {
        try {
            DB::beginTransaction();
            if ($request->type_study_program == 'all') {
                $studyPrograms = StudyProgram::select('id')->whereIsActive(true)->get();
                // return response()->json(['data' => $studyPrograms], 500);
                foreach ($studyPrograms as $key => $value) {
                    // $this->check($value->id, $request);
                    // $this->insertData($value->id, $request);
                    $checkScoreScale = ScoreScale::where(['study_program_id' => $value->id, 'min_score' => $request->min_score, 'max_score' => $request->max_score])->count();

                    if ($checkScoreScale > 0) {
                        return response()->json([
                            'message' => 'Data dengan program studi, nilai minimal, dan nilai maksimal tersebut sudah ada'
                        ], 500);
                    }

                    if ($request->is_score_def == 'true') {
                        $checkIsScoreDef = ScoreScale::where(['study_program_id' => $value->id, 'is_score_def' => $request->is_score_def])->count();

                        if ($checkIsScoreDef > 0) {
                            return response()->json([
                                'message' => 'Data dengan program studi dan nilai default aktif tersebut sudah ada'
                            ], 500);
                        }
                    }

                    ScoreScale::create([
                        'id' => Uuid::uuid4(),
                        'study_program_id' => $value->id,
                        'grade' => $request->grade,
                        'index_score' => $request->index_score,
                        'min_score' => $request->min_score,
                        'max_score' => $request->max_score,
                        'date_start' => $request->year_start . '-01-01',
                        'date_end' => $request->year_end . '-12-01',
                        'is_score_def' => $request->is_score_def
                    ]);
                }
            } else {
                foreach ($request->study_program as $key => $value) {
                    // $this->check(null, $request);
                    $checkScoreScale = ScoreScale::where(['study_program_id' => $value, 'min_score' => $request->min_score, 'max_score' => $request->max_score])->count();

                    if ($checkScoreScale > 0) {
                        return response()->json([
                            'message' => 'Data dengan program studi, nilai minimal, dan nilai maksimal tersebut sudah ada'
                        ], 500);
                    }

                    if ($request->is_score_def == 'true') {
                        $checkIsScoreDef = ScoreScale::where(['study_program_id' => $value, 'is_score_def' => $request->is_score_def])->count();

                        if ($checkIsScoreDef > 0) {
                            return response()->json([
                                'message' => 'Data dengan program studi dan nilai default aktif tersebut sudah ada'
                            ], 500);
                        }
                    }

                    ScoreScale::create([
                        'id' => Uuid::uuid4(),
                        'study_program_id' => $value,
                        'grade' => $request->grade,
                        'index_score' => $request->index_score,
                        'min_score' => $request->min_score,
                        'max_score' => $request->max_score,
                        'date_start' => $request->year_start . '-01-01',
                        'date_end' => $request->year_end . '-12-01',
                        'is_score_def' => $request->is_score_def
                    ]);
                    // $this->insertData(null, $request);
                }
            }
            DB::commit();

            return $this->successResponse('Berhasil membuat data skala nilai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(ScoreScale $scoreScale, ScoreScaleRequest $request)
    {
        try {
            $checkScoreScale = ScoreScale::where(['study_program_id' => $request->study_program, 'min_score' => $request->min_score, 'max_score' => $request->max_score])->where('id', '!=', $scoreScale->id)->count();

            if ($checkScoreScale > 0) {
                return response()->json([
                    'message' => 'Data dengan program studi, nilai minimal, dan nilai maksimal tersebut sudah ada'
                ], 500);
            }

            if ($request->is_score_def == 'true') {
                $checkIsScoreDef = ScoreScale::where(['study_program_id' => $request->study_program, 'is_score_def' => $request->is_score_def])->where('id', '!=', $scoreScale->id)->count();

                if ($checkIsScoreDef > 0) {
                    return response()->json([
                        'message' => 'Data dengan program studi dan nilai default aktif tersebut sudah ada'
                    ], 500);
                }
            }

            $scoreScale->update([
                'study_program_id' => $request->study_program,
                'grade' => $request->grade,
                'index_score' => $request->index_score,
                'min_score' => $request->min_score,
                'max_score' => $request->max_score,
                'date_start' => $request->year_start . '-01-01',
                'date_end' => $request->year_end . '-12-01',
                'is_score_def' => $request->is_score_def
            ]);

            return $this->successResponse('Berhasil mengupdate data skala nilai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(ScoreScale $scoreScale)
    {
        try {
            $scoreScale->delete();

            return $this->successResponse('Berhasil menghapus data skala nilai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    // private function insertData($studyProgramId = null, Request $request)
    // {
    //     try {
    //         $res = ScoreScale::create([
    //             'id' => Uuid::uuid4(),
    //             'study_program_id' => $studyProgramId ?? $request->study_program,
    //             'grade' => $request->grade,
    //             'index_score' => $request->index_score,
    //             'min_score' => $request->min_score,
    //             'max_score' => $request->max_score,
    //             'date_start' => $request->year_start . '-01-01',
    //             'date_end' => $request->year_end . '-12-01',
    //             'is_score_def' => $request->is_score_def
    //         ]);
    //         return $res;
    //     } catch (Exception $e) {
    //         return $this->exceptionResponse($e);
    //     }
    // }

    // private function check($studyProgramId = null, Request $request)
    // {
    //     try {
    //         $checkScoreScale = ScoreScale::where(['study_program_id' => $studyProgramId ?? $request->study_program, 'min_score' => $request->min_score, 'max_score' => $request->max_score])->count();

    //         if ($checkScoreScale > 0) {
    //             return response()->json([
    //                 'message' => 'Data dengan program studi, nilai minimal, dan nilai maksimal tersebut sudah ada'
    //             ], 500);
    //         }

    //         if ($request->is_score_def == 'true') {
    //             $checkIsScoreDef = ScoreScale::where(['study_program_id' => $studyProgramId ?? $request->study_program, 'is_score_def' => $request->is_score_def])->count();

    //             if ($checkIsScoreDef > 0) {
    //                 return response()->json([
    //                     'message' => 'Data dengan program studi dan nilai default aktif tersebut sudah ada'
    //                 ], 500);
    //             }
    //         }
    //     } catch (Exception $e) {
    //         return $this->exceptionResponse($e);
    //     }
    // }
}
