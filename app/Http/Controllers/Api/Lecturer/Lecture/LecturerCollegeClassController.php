<?php

namespace App\Http\Controllers\Api\Lecturer\Lecture;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\CollegeContract;
use App\Models\Course;
use App\Models\ExamSchedule;
use App\Models\TeachingLecturer;
use App\Models\WeeklySchedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class LecturerCollegeClassController extends Controller
{
    public function getCourses()
    {
        $courses = Course::whereHas('collegeClass', function ($q) {
            $q->whereHas('teachingLecturer', function ($q) {
                $q->where('employee_id', getInfoLogin()->userable->id);
            });
            $q->where('academic_period_id', getActiveAcademicPeriod(true, false)->id);
        })->distinct()->get();

        return $this->successResponse(null, compact('courses'));
    }

    public function getCollegeClasses(Request $request)
    {
        $collegeClasses = CollegeClass::where(['course_id' => $request->course_id, 'academic_period_id' => getActiveAcademicPeriod(true, false)->id])->whereHas('teachingLecturer', function ($q) {
            $q->where('employee_id', getInfoLogin()->userable->id);
        })->with('lectureSystem')->withCount('classParticipant')->get();

        return $this->successResponse(null, compact('collegeClasses'));
    }

    public function index(Request $request)
    {
        $query = CollegeClass::query();

        // return response()->json([
        //     'data' => $request->college_class_id != 'null'
        // ]);

        if ($request->has('course_id') and $request->course_id != '' and $request->course_id != 'null') {
            $query = $query->where('course_id', $request->course_id);
        }

        if ($request->has('college_class_id') and $request->college_class_id != '' and $request->college_class_id != 'null') {
            $query = $query->whereId($request->college_class_id);
        }

        return DataTables::of($query->whereHas('teachingLecturer', function ($q) {
            $q->where('employee_id', getInfoLogin()->userable->id);
        })->where('academic_period_id', getActiveAcademicPeriod(true, false)->id)->with(['academicPeriod', 'course', 'studyProgram.educationLevel', 'classParticipant', 'score']))
            ->addColumn('score_count', function ($data) {
                return count($data->score);
            })
            ->addColumn('participant_count', function ($data) {
                return count($data->classParticipant);
            })->make(true);
    }

    public function show(CollegeClass $collegeClass)
    {
        isset($collegeClass->studyProgram->educationLevel) ? $collegeClass->studyProgram->educationLevel : '';
        $collegeClass->academicPeriod;
        $collegeClass->course;
        $collegeClass->lectureSystem;
        $collegeClass->classParticipantCount = $collegeClass->classParticipant->count();

        return $this->successResponse(null, compact('collegeClass'));
    }

    public function weeklySchedule(CollegeClass $collegeClass)
    {
        return DataTables::of(WeeklySchedule::where('college_class_id', $collegeClass->id)->with(['collegeClass', 'day', 'meetingType', 'room']))
            ->addColumn('formatted_time', function ($data) {
                return date('H:i', strtotime($data->time_start)) . ' - ' . date('H:i', strtotime($data->time_end));
            })
            ->make(true);
    }

    public function teachingLecturer(CollegeClass $collegeClass)
    {
        return DataTables::of(TeachingLecturer::where('college_class_id', $collegeClass->id)->with(['employee', 'weeklySchedule.day', 'weeklySchedule.room', 'collegeClass']))
            ->addColumn('schedule', function ($data) {
                if ($data->weekly_schedule_id == null) return '-';

                return $data->weeklySchedule->day->name . ' - ' . Carbon::parse($data->weeklySchedule->time_start)->isoFormat('HH:mm') . ' s/d ' . Carbon::parse($data->weeklySchedule->time_end)->isoFormat('HH:mm') . ' (' . ($data->weeklySchedule?->room?->name ?? '-') . ')';
            })
            ->make(true);
    }

    public function classSchedule(CollegeClass $collegeClass, Request $request)
    {
        return DataTables::of(ClassSchedule::where('college_class_id', $collegeClass->id)->with(['room', 'employee', 'meetingType', 'collegeClass'])->orderBy('meeting_number', 'ASC'))
            ->addColumn('formatted_date', function ($data) {
                return $data->date ? idDay(date('N', strtotime($data->date))) . ', ' . date('d-m-Y', strtotime($data->date)) : '-';
            })
            ->addColumn('formatted_time_range', function ($data) {
                return date('H:i', strtotime($data->time_start)) . ' - ' . date('H:i', strtotime($data->time_end));
            })
            ->make(true);
    }

    public function classParticipant(CollegeClass $collegeClass)
    {
        return DataTables::of(ClassParticipant::where('college_class_id', $collegeClass->id)->with(['student', 'collegeClass']))
            ->addColumn('student_name', function ($data) {
                return $data->student->name;
            })
            ->addColumn('student_nim', function ($data) {
                return $data->student->nim;
            })
            ->addColumn('student_gender', function ($data) {
                return $data->student->gender;
            })
            ->make(true);
    }

    public function collegeContract(CollegeClass $collegeClass)
    {
        try {
            $collegeContract = $collegeClass->collegeContract;

            return $this->successResponse(null, compact('collegeContract', 'collegeClass'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function createOrUpdateCollegeContract(CollegeClass $collegeClass, Request $request)
    {
        $request->validate([
            'content' => 'required',
            'file' => 'required|mimes:pdf,docx,doc'
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = 'College_Contracts_' . time() . rand(0, 99999999999) . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/documents/college-contracts'), $fileName);
            }

            // $request->merge(['attachment' => $fileName, 'college_class_id' => $collegeClass->id]);
            if (is_null($collegeClass->collegeContract)) {
                CollegeContract::create([
                    'id' => Uuid::uuid4(),
                    'college_class_id' => $collegeClass->id,
                    'content' => $request->content,
                    'attachment' => $fileName
                ]);
            } else {
                if (file_exists(public_path('storage/documents/college-contracts/' . $collegeClass->collegeContract->attachment))) {
                    File::delete(public_path('storage/documents/college-contracts/' . $collegeClass->collegeContract->attachment));
                }

                CollegeContract::whereCollegeClassId($collegeClass->id)->update([
                    'attachment' => $fileName,
                    'content' => $request->content
                ]);
            }

            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function examSchedule(CollegeClass $collegeClass)
    {
        return DataTables::of(ExamSchedule::where('college_class_id', $collegeClass->id)->with(['employee1', 'employee2', 'room', 'meetingType', 'collegeClass']))
            ->addColumn('date', function ($data) {
                return Carbon::parse($data->date)->isoFormat('dddd, D MMM Y');
            })
            ->addColumn('time', function ($data) {
                return Carbon::parse($data->time_start)->isoFormat('HH:mm') . ' s/d ' . Carbon::parse($data->time_end)->isoFormat('HH:mm');
            })
            ->addColumn('location', function ($data) {
                return $data->type == 'offline' ? $data->room?->name . ' / ' . $data->location : $data->location;
            })
            ->addColumn('participant_count', function ($data) {
                return count($data->collegeClass->classParticipant);
            })
            ->make(true);
    }
}
