<?php

namespace App\Http\Controllers\Api\Lecturer\Lecture;

use App\Http\Controllers\Controller;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Score;
use App\Models\ScorePercentage;
use App\Models\ScoreScale;
use App\Models\StudyProgramSetting;
use App\Models\TeachingLecturer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class LecturerStudentGradeController extends Controller
{

    public function getCourses(Request $request)
    {
        $courses = Course::whereHas('collegeClass', function ($q) use ($request) {
            $q->whereHas('teachingLecturer', function ($q) {
                $q->where('employee_id', getInfoLogin()->userable->id);
            });
            $q->where('academic_period_id', getActiveAcademicPeriod(true)->id);
        })->distinct()->get();

        return $this->successResponse(null, compact('courses'));
    }

    public function getCollegeClasses(Request $request)
    {
        $collegeClasses = CollegeClass::where(['course_id' => $request->course_id, 'academic_period_id' => getActiveAcademicPeriod(true)->id])->whereHas('teachingLecturer', function ($q) {
            $q->where('employee_id', getInfoLogin()->userable->id);
        })->with('lectureSystem')->withCount('classParticipant')->get();

        return $this->successResponse(null, compact('collegeClasses'));
    }

    public function index(Request $request)
    {
        $scorePercentage = ScorePercentage::where('college_class_id', $request->college_class_id)->with('collegeClass')->whereHas('collegeClass', function ($q) {
            $q->whereHas('teachingLecturer', function ($q) {
                $q->where('employee_id', getInfoLogin()->userable->id);
            });
            $q->where('academic_period_id', getActiveAcademicPeriod(true)->id);
        })->first();
        $classParticipants = ClassParticipant::where('college_class_id', $request->college_class_id)->whereHas('collegeClass', function ($q) {
            $q->whereHas('teachingLecturer', function ($q) {
                $q->where('employee_id', getInfoLogin()->userable->id);
            });
            $q->where('academic_period_id', getActiveAcademicPeriod(true)->id);
        })
            ->with(['student' => function ($q) use ($request) {
                $q->with(['score' => function ($q) use ($request) {
                    $q->where('college_class_id', $request->college_class_id);
                }]);
            }])->with('collegeClass.studyProgram')->get();

        return $this->successResponse(null, compact('classParticipants', 'scorePercentage'));
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
            if ($request->has('college_class_id') and $request->college_class_id != '' and $request->has('student_id') and count($request->student_id) > 0) {
                $checkTeachingLecturer = TeachingLecturer::whereHas('collegeClass', function ($q) {
                    $q->where('academic_period_id', getActiveAcademicPeriod(true)->id);
                })->where(['college_class_id' => $request->college_class_id, 'employee_id' => getInfoLogin()->userable->id])->first();
                if (is_null($checkTeachingLecturer) or !$checkTeachingLecturer->is_score_entry) {
                    return response()->json([
                        'message' => 'Opps! Anda tidak memiliki akses untuk input nilai'
                    ], 500);
                } else {
                    $collegeClass = CollegeClass::find($request->college_class_id);

                    $checkScoreEntry = StudyProgramSetting::where(['study_program_id' => $collegeClass->study_program_id, 'academic_period_id' => getActiveAcademicPeriod(true)->id]);
                    $scoreEntry = $checkScoreEntry->first();

                    $dateNow = Carbon::now();

                    if ($checkScoreEntry->where('date_start_score', '<=', $dateNow)->where('date_end_score', '>=', $dateNow)->count() > 0 or (!is_null($scoreEntry) and $scoreEntry->is_score)) {
                        $batchInsert = [];
                        DB::beginTransaction();

                        $scorePercentage = ScorePercentage::where('college_class_id', $request->college_class_id);
                        if ($scorePercentage->count() > 0) {
                            $scorePercentage->update([
                                'quiz' => $request->score_percentage_quiz,
                                'coursework' => $request->score_percentage_coursework,
                                'attendance' => $request->score_percentage_attendance,
                                'mid_exam' => $request->score_percentage_mid_exam,
                                'final_exam' => $request->score_percentage_final_exam,
                                'practice' => $request->score_percentage_practice
                            ]);
                        } else {
                            ScorePercentage::create([
                                'college_class_id' => $request->college_class_id,
                                'quiz' => $request->score_percentage_quiz,
                                'coursework' => $request->score_percentage_coursework,
                                'attendance' => $request->score_percentage_attendance,
                                'mid_exam' => $request->score_percentage_mid_exam,
                                'final_exam' => $request->score_percentage_final_exam,
                                'practice' => $request->score_percentage_practice
                            ]);
                        }

                        $checkInputRemedialScore = StudyProgramSetting::where(['study_program_id' => $collegeClass->study_program_id, 'academic_period_id' => getActiveAcademicPeriod(true)->id]);

                        foreach ($request->student_id as $key => $value) {
                            // check
                            $checkScore = Score::whereStudentId($value)->whereCollegeClassId($collegeClass->id);
                            $scoreScale = ScoreScale::where('study_program_id', $collegeClass->study_program_id);

                            // return response()->json([
                            //     'res' => 
                            // ], 500);

                            if ($scoreScale->count() > 0) {
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

                            if ($request->has('remedial_score') and $request->remedial_score[$key] != '' and $request->remedial_score[$key] != 0 and $checkInputRemedialScore->where('date_start_remedial_score', '<=', $dateNow)->where('date_end_remedial_score', '>=', $dateNow)->count() <= 0 and !is_null($scoreEntry) and !$scoreEntry->is_remedial_score) {
                                return response()->json([
                                    'message' => 'Opps! Input nilai remidi belum di izinkan'
                                ], 500);
                            } else {
                                $scoreScale = $scoreScale->where('min_score', '<=', ceil($request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key]))->where('max_score', '>=', ceil($request->remedial_score[$key] == 0 ? $request->score[$key] : $request->remedial_score[$key]));
                                $scoreScale = $scoreScale->first();

                                // return response()->json([
                                //     'res' => $request->remedial_score[1]
                                // ], 500);

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
                        }
                        Score::insert($batchInsert);
                        DB::commit();

                        return $this->successResponse('Berhasil menyimpan data nilai mahasiswa');
                    } else {
                        return response()->json([
                            'message' => 'Opps! Input nilai belum di izinkan'
                        ], 500);
                    }
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

    public function lock(Request $request)
    {
        try {
            if (!empty($request->college_class_id) and $request->has('college_class_id')) {
                $checkTeachingLecturer = TeachingLecturer::whereHas('collegeClass', function ($q) {
                    $q->where('academic_period_id', getActiveAcademicPeriod(true)->id);
                })->where(['college_class_id' => $request->college_class_id, 'employee_id' => getInfoLogin()->userable->id])->first();

                if (is_null($checkTeachingLecturer) or !$checkTeachingLecturer->is_score_entry) {
                    return response()->json([
                        'message' => 'Opps! Anda tidak memiliki akses untuk mengunci/membuka nilai'
                    ], 500);
                } else {
                    $collegeClass = CollegeClass::whereId($request->college_class_id);
                    $oldCollegeClass = $collegeClass->first();
                    CollegeClass::whereId($request->college_class_id)->update(['is_lock_score' => !$oldCollegeClass->is_lock_score]);
                    $newCollegeClass = $collegeClass->first();

                    if ($newCollegeClass->is_lock_score) {
                        return $this->successResponse('Berhasil mengunci nilai mahasiswa');
                    } else {
                        return $this->successResponse('Berhasil membuka nilai mahasiswa');
                    }
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
            if (!empty($request->college_class_id) and $request->has('college_class_id')) {
                $checkTeachingLecturer = TeachingLecturer::whereHas('collegeClass', function ($q) {
                    $q->where('academic_period_id', getActiveAcademicPeriod(true)->id);
                })->where(['college_class_id' => $request->college_class_id, 'employee_id' => getInfoLogin()->userable->id])->first();

                if (is_null($checkTeachingLecturer) or !$checkTeachingLecturer->is_score_entry) {
                    return response()->json([
                        'message' => 'Opps! Anda tidak memiliki akses untuk mempublish/unpublish nilai'
                    ], 500);
                } else {
                    $score = Score::whereCollegeClassId($request->college_class_id);
                    $oldScore = $score->first();
                    Score::whereCollegeClassId($request->college_class_id)->update([
                        'is_publish' => !$oldScore->is_publish
                    ]);
                    $newScore = $score->first();

                    if ($newScore->is_publish) {
                        return $this->successResponse('Berhasil mempublish nilai mahasiswa');
                    } else {
                        return $this->successResponse('Berhasil unpublish nilai mahasiswa');
                    }
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
