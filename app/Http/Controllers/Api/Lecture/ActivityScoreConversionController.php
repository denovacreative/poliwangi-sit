<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\ActivityScoreConversionRequest;
use App\Models\ActivityScoreConversion;
use App\Models\Course;
use App\Models\ScoreScale;
use App\Models\StudentActivityMember;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class ActivityScoreConversionController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentActivityMember::with('studentActivity.academicPeriod', 'studentActivity.studyProgram.educationLevel', 'student', 'studentActivity.studentActivityCategory', 'activityScoreConversion')
            ->whereHas('studentActivity', function ($q) {
                $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
            });
        if (!is_null($request->study_program) and $request->study_program != '' and $request->study_program != 'all') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program);
            });
        }
        if (!is_null($request->academic_period) and $request->academic_period != '' and $request->academic_period != 'all') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period);
            });
        }
        if (!is_null($request->student_activity_category) and $request->student_activity_category != '' and $request->student_activity_category != 'all') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('student_activity_category_id', $request->student_activity_category);
            });
        }
        if (mappingAccess() != null) {
            $query->whereHas('studentActivity', function ($q) {
                $q->whereIn('study_program_id', mappingAccess());
            });
        }
        return DataTables::of($query)->addColumn('credit_conversion', function ($data) {
            return $data->activityScoreConversion != null ? $data->activityScoreConversion->sum('credit') : 0;
        })->make();
    }

    public function getData(StudentActivityMember $studentActivityMember)
    {
        $query = ActivityScoreConversion::where(['student_activity_member_id' => $studentActivityMember->id])
            ->with(['course']);
        return DataTables::of($query)->make(true);
    }

    public function detail(StudentActivityMember $studentActivityMember)
    {
        try {
            $data = StudentActivityMember::where(['id' => $studentActivityMember->id])->with(['student.classGroup', 'student.studyProgram.educationLevel', 'student.academicPeriod', 'student.studentStatus', 'studentActivity.studentActivityCategory'])->first();
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function store(StudentActivityMember $studentActivityMember, ActivityScoreConversionRequest $request)
    {
        try {
            $findCourse = Course::where(['id' => $request->course])->first();
            $findScoreScale = ScoreScale::where(['grade' => $request->grade, 'study_program_id' => $studentActivityMember->studentActivity->study_program_id])->first();
            ActivityScoreConversion::create([
                'id' => Uuid::uuid4(),
                'course_id' => $request->course,
                'student_activity_member_id' => $studentActivityMember->id,
                'student_activity_id' => $studentActivityMember->student_activity_id,
                'credit' => $findCourse->credit_total,
                'score' => $request->score,
                'grade' => $findScoreScale->grade,
                'index_score' => $findScoreScale->index_score,
            ]);
            return $this->successResponse('Berhasil menambahkan konversi nilai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function update(StudentActivityMember $studentActivityMember, ActivityScoreConversion $activityScoreConversion, ActivityScoreConversionRequest $request)
    {
        try {
            $findCourse = Course::where(['id' => $request->course])->first();
            $findScoreScale = ScoreScale::where(['grade' => $request->grade, 'study_program_id' => $studentActivityMember->studentActivity->study_program_id])->first();
            $activityScoreConversion->update([
                'course_id' => $request->course,
                'student_activity_member_id' => $studentActivityMember->id,
                'student_activity_id' => $studentActivityMember->student_activity_id,
                'credit' => $findCourse->credit_total,
                'score' => $request->score,
                'grade' => $findScoreScale->grade,
                'index_score' => $findScoreScale->index_score,
            ]);
            return $this->successResponse('Berhasil merubah konversi nilai');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function show(StudentActivityMember $studentActivityMember, ActivityScoreConversion $activityScoreConversion)
    {
        try {
            return $this->successResponse('Berhasil mengambil data konversi nilai', compact('activityScoreConversion'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function updateStatus(StudentActivityMember $studentActivityMember, ActivityScoreConversion $activityScoreConversion)
    {
        try {
            $activityScoreConversion->update(['is_transcript' => !$activityScoreConversion->is_transcript]);
            return $this->successResponse('Berhasil mengubah status');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function destroy(StudentActivityMember $studentActivityMember, ActivityScoreConversion $activityScoreConversion)
    {
        try {
            $activityScoreConversion->delete();
            return $this->successResponse('Berhasil menghapus konversi nilai');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
