<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\Score;
use App\Models\ScorePercentage;
use App\Models\ScoreScale;
use App\Models\TeachingLecturer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Nonstandard\Uuid;

class StudentGradeController extends Controller
{
    public function getCourses(Request $request)
    {
        $courses = Course::whereHas('collegeClass', function ($q) use ($request) {
            $q->where(['academic_period_id' => $request->academic_period, 'study_program_id' => $request->study_program]);
        })->get();

        return $this->successResponse(null, compact('courses'));
    }

    public function getCollegeClasses(Request $request)
    {
        $collegeClasses = CollegeClass::where(['academic_period_id' => $request->academic_period, 'study_program_id' => $request->study_program, 'course_id' => $request->course])->with('lectureSystem')->withCount('classParticipant')->get();

        return $this->successResponse(null, compact('collegeClasses'));
    }

    public function index(Request $request)
    {
        try {
            $scorePercentage = ScorePercentage::where('college_class_id', $request->college_class)->with('collegeClass')->first();
            $classParticipants = ClassParticipant::where('college_class_id', $request->college_class)->with(['student' => function ($q) use ($request) {
                $q->with(['score' => function ($q) use ($request) {
                    $q->where('college_class_id', $request->college_class);
                }]);
            }])->with('collegeClass.studyProgram')->get();
            return $this->successResponse(null, compact('classParticipants', 'scorePercentage'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function insertOrUpdate(Request $request)
    {
        $request->validate([
            'score_percentage_quiz' => 'required|numeric',
            'score_percentage_coursework' => 'required|numeric',
            'score_percentage_attendance' => 'required|numeric',
            'score_percentage_mid_exam' => 'required|numeric',
            'score_percentage_final_exam' => 'required|numeric',
            'score_percentage_practice' => 'required|numeric',
        ]);

        try {
            if (($request->has('college_class') and $request->college_class != '') and ($request->has('student') and count($request->student) > 0)) {
                // $checkTeachingLecturer = TeachingLecturer::where(['college_class_id' => $request->college_class_id, 'is_score_entry' => true])->count();
                $batchInsert = [];
                DB::beginTransaction();

                $scorePercentage = ScorePercentage::where('college_class_id', $request->college_class);
                if ($scorePercentage->count() > 0) {
                    $scorePercentage->update([
                        'quiz' => $request->score_percentage_quiz ?? 0,
                        'coursework' => $request->score_percentage_coursework ?? 0,
                        'attendance' => $request->score_percentage_attendance ?? 0,
                        'mid_exam' => $request->score_percentage_mid_exam ?? 0,
                        'final_exam' => $request->score_percentage_final_exam ?? 0,
                        'practice' => $request->score_percentage_practice ?? 0
                    ]);
                } else {
                    $data = ScorePercentage::create([
                        'college_class_id' => $request->college_class,
                        'quiz' => $request->score_percentage_quiz ?? 0,
                        'coursework' => $request->score_percentage_coursework ?? 0,
                        'attendance' => $request->score_percentage_attendance ?? 0,
                        'mid_exam' => $request->score_percentage_mid_exam ?? 0,
                        'final_exam' => $request->score_percentage_final_exam ?? 0,
                        'practice' => $request->score_percentage_practice ?? 0
                    ]);
                }

                $collegeClass = CollegeClass::find($request->college_class);

                foreach ($request->student as $key => $value) {
                    // check
                    $checkScore = Score::whereStudentId($request->student[$key])->whereCollegeClassId($collegeClass->id);
                    $scoreScale = ScoreScale::where('study_program_id', $collegeClass->study_program_id);

                    if ($scoreScale->count() > 0) {
                        $dateNow = Carbon::now();
                        $scoreScale = $scoreScale->where('date_start', '<=', $dateNow)->where('date_end', '>=', $dateNow);
                        if ($scoreScale->count() <= 0) {
                            return response()->json([
                                'message' => 'Data skala nilai telah expired'
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'message' => 'Skala nilai belum di setting'
                        ], 500);
                    }
                    $scoreScale = $scoreScale->where('min_score', '<=', ceil($request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key]))->where('max_score', '>=', ceil($request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key]));
                    $scoreScale = $scoreScale->first();
                    // return response()->json([
                    //     'data' => $scoreScale
                    // ]);

                    if ($checkScore->count() > 0) {
                        $score = $checkScore->first();
                        $checkScore->update([
                            'mid_exam' => $request->mid_exam[$key] ?? 0,
                            'final_exam' => $request->final_exam[$key] ?? 0,
                            'coursework' => $request->coursework[$key] ?? 0,
                            'quiz' => $request->quiz[$key] ?? 0,
                            'attendance' => $request->attendance[$key] ?? 0,
                            'practice' => $request->practice[$key] ?? 0,
                            'final_score' => $request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key],
                            'remedial_score' => $request->remedial_score[$key] == 0 ? null : $request->remedial_score[$key],
                            // 'final_grade' => $request->remedial_score[$key] == 0 ? $scoreScale->grade ?? 'E' : $score->grade,
                            'final_grade' => $scoreScale->grade,
                            'score' => $request->score[$key],
                            // 'grade' => $scoreScale->grade ?? 'E',
                            'grade' => $request->remedial_score[$key] == 0 ? $scoreScale->grade : $score->grade,
                            'index_score' => $scoreScale->index_score ?? 0,
                        ]);
                    } else {
                        $batchInsert[] = [
                            'id' => Uuid::uuid4(),
                            'college_class_id' => $collegeClass->id,
                            'student_id' => $value,
                            'mid_exam' => $request->mid_exam[$key],
                            'final_exam' => $request->final_exam[$key],
                            'coursework' => $request->coursework[$key],
                            'quiz' => $request->quiz[$key],
                            'attendance' => $request->attendance[$key],
                            'practice' => $request->practice[$key],
                            'final_score' => $request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key],
                            'remedial_score' => $request->remedial_score[$key] == 0 ? null : $request->remedial_score[$key],
                            // 'final_grade' => $request->remedial_score[$key] == 0 ? $scoreScale->grade ?? 'E' : 'E',
                            'final_grade' => $scoreScale->grade,
                            'score' => $request->score[$key],
                            'grade' => $scoreScale->grade ?? 'E',
                            'index_score' => $scoreScale->index_score ?? 0,
                        ];
                    }
                }
                // if ($checkTeachingLecturer <= 0) {
                //     return response()->json([
                //         'message' => 'Data dosen yang input nilai tidak ada'
                //     ], 500);
                // } else {
                // }

                Score::insert($batchInsert);
                DB::commit();

                return $this->successResponse('Berhasil menyimpan data nilai mahasiswa');
            } else {
                return response()->json([
                    'message' => 'Data nilai mahasiswa tidak ada'
                ], 500);
            }
            // if (!empty($request->score_id) and count($request->score_id) > 0) {
            //     // $scorePercentage = ScorePercentage::whereId($request->score_percentage);
            //     if (empty($request->employee)) {
            //         return response()->json([
            //             'message' => 'Data dosen yang input nilai tidak ada'
            //         ], 500);
            //     } else {
            //         DB::beginTransaction();
            //         if (!empty($request->score_percentage)) {
            //             ScorePercentage::whereId($request->score_percentage)->update([
            //                 'quiz' => $request->score_percentage_quiz,
            //                 'coursework' => $request->score_percentage_coursework,
            //                 'attendance' => $request->score_percentage_attendance,
            //                 'mid_exam' => $request->score_percentage_mid_exam,
            //                 'final_exam' => $request->score_percentage_final_exam,
            //                 'practice' => $request->score_percentage_practice
            //             ]);
            //         } else {
            //             ScorePercentage::create([
            //                 'college_class_id' => $request->college_class,
            //                 'quiz' => $request->score_percentage_quiz,
            //                 'coursework' => $request->score_percentage_coursework,
            //                 'attendance' => $request->score_percentage_attendance,
            //                 'mid_exam' => $request->score_percentage_mid_exam,
            //                 'final_exam' => $request->score_percentage_final_exam,
            //                 'practice' => $request->score_percentage_practice
            //             ]);
            //         }

            //         $scoreScale = ScoreScale::where('study_program_id', $request->study_program);

            //         if ($scoreScale->count() > 0) {
            //             $dateNow = Carbon::now();
            //             $scoreScale = $scoreScale->where('date_start', '<=', $dateNow)->where('date_end', '>=', $dateNow);
            //             if ($scoreScale->count() <= 0) {
            //                 return response()->json([
            //                     'message' => 'Data skala nilai telah expired'
            //                 ], 500);
            //             }
            //         } else {
            //             return response()->json([
            //                 'message' => 'Skala nilai belum di setting'
            //             ], 500);
            //         }

            //         foreach ($request->score_id as $key => $value) {
            //             $scoreScale = $scoreScale->where('min_score', '<=', $request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key])->where('max_score', '>=', $request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key]);
            //             if ($scoreScale->count() > 0) {
            //                 $scoreScale = $scoreScale->first();
            //                 $score = Score::whereId($value);
            //                 $oldScore = $score->first();
            //                 $score->update([
            //                     'mid_exam' => $request->mid_exam[$key],
            //                     'final_exam' => $request->final_exam[$key],
            //                     'coursework' => $request->coursework[$key],
            //                     'quiz' => $request->quiz[$key],
            //                     'attendance' => $request->attendance[$key],
            //                     'practice' => $request->practice[$key],
            //                     'final_score' => $request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key],
            //                     'remedial_score' => $request->remedial_score[$key] == 0 ? null : $request->remedial_score[$key],
            //                     'final_grade' => $request->remedial_score[$key] == 0 ? $scoreScale->grade ?? 'E' : $oldScore->grade,
            //                     'score' => $request->remedial_score[$key] == 0 ? $request->score[$key] : $oldScore->score,
            //                     'grade' => $scoreScale->grade ?? 'E',
            //                     'index_score' => $scoreScale->index_score ?? 0,
            //                     'description' => '-'
            //                 ]);
            //             } else {
            //                 return response()->json([
            //                     'message' => 'Skala nilai belum di setting'
            //                 ], 500);
            //             }
            //         }
            //         DB::commit();
            //         return $this->successResponse('Berhasil mengupdate data nilai mahasiswa');
            //     }
            // } else {
            //     return response()->json([
            //         'message' => 'Data nilai mahasiswa tidak ada'
            //     ], 500);
            // }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function lock(Request $request)
    {
        try {
            if (!empty($request->college_class) and $request->has('college_class')) {
                $collegeClass = CollegeClass::whereId($request->college_class);
                $oldCollegeClass = $collegeClass->first();
                CollegeClass::whereId($request->college_class)->update(['is_lock_score' => !$oldCollegeClass->is_lock_score]);
                $newCollegeClass = $collegeClass->first();

                if ($newCollegeClass->is_lock_score) {
                    return $this->successResponse('Berhasil mengunci nilai mahasiswa');
                } else {
                    return $this->successResponse('Berhasil membuka nilai mahasiswa');
                }
            } else {
                return response()->json([
                    'message' => 'Data nilai mahasiswa tidak ada'
                ], 500);
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function publish(Request $request)
    {
        try {
            if (!empty($request->college_class) and $request->has('college_class')) {
                $score = Score::whereCollegeClassId($request->college_class);
                $oldScore = $score->first();
                Score::whereCollegeClassId($request->college_class)->update([
                    'is_publish' => !$oldScore->is_publish
                ]);
                $newScore = $score->first();

                if ($newScore->is_publish) {
                    return $this->successResponse('Berhasil mempublish nilai mahasiswa');
                } else {
                    return $this->successResponse('Berhasil unpublish nilai mahasiswa');
                }
            } else {
                return response()->json([
                    'message' => 'Data nilai mahasiswa tidak ada'
                ], 500);
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
