<?php

namespace App\Http\Controllers\Api\Lecturer\Lecture;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\Presence;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LecturerPresenceController extends Controller
{
    public function getCourses()
    {
        $courses = Course::whereHas('collegeClass', function ($q) {
            $q->whereHas('teachingLecturer', function ($q) {
                $q->where('employee_id', getInfoLogin()->userable->id);
            });
            $q->where('academic_period_id', AcademicPeriod::where('is_use', true)->first()->id);
        })->distinct()->get();
        return $this->successResponse(null, compact('courses'));
    }

    public function getCollegeClasses(Request $request)
    {
        $collegeClasses = CollegeClass::where(['course_id' => $request->course_id, 'academic_period_id' => AcademicPeriod::where('is_use', true)->first()->id])->whereHas('teachingLecturer', function ($q) {
            $q->where('employee_id', getInfoLogin()->userable->id);
        })->with('lectureSystem')->withCount('classParticipant')->get();
        return $this->successResponse(null, compact('collegeClasses'));
    }

    public function getClassParticipants(Request $request)
    {
        try {
            $classSchedule = ClassSchedule::where($request->only('college_class_id'))->orderBy('meeting_number', 'asc')->get();
            $classParticipant = ClassParticipant::where($request->only('college_class_id'))->get();

            $headers = [];
            $collections = [];
            foreach ($classParticipant as $item) {
                $temp = [];
                $temp['id'] = $item->student->id;
                $temp['name'] = $item->student->name;
                foreach ($classSchedule as $schedule) {
                    $presence = $item->student->presence()->where($request->only('college_class_id'))->where('number_of_meeting', $schedule->meeting_number)->first();
                    $temp['meeting_of_' . $schedule->meeting_number] = !is_null($presence) ? $presence->status : '0';
                }
                $collections[] = $temp;
            }

            foreach ($classSchedule as $item) {
                $headers[] = $item->meeting_number;
            }

            return $this->successResponse(null, compact('headers', 'collections'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            if (!is_null($request->student_id)) {
                DB::beginTransaction();
                $classSchedule = ClassSchedule::where('college_class_id', $request->college_class_id)->orderBy('meeting_number', 'asc')->get();
                $collections = [];
                foreach ($request->student_id as $key => $item) {
                    foreach ($classSchedule as $schedule) {
                        if ($schedule->status == 'done') {
                            $check = Presence::where('student_id', $request->student_id[$key])->where('college_class_id', $request->college_class_id)->where('class_schedule_id', $schedule->id);

                            if ($check->count() > 0) {
                                $check->update([
                                    'status' => $request->{'meeting_' . $schedule->meeting_number}[$key]
                                ]);
                            } else {
                                $temp = [
                                    'college_class_id' => $request->college_class_id,
                                    'class_schedule_id' => $schedule->id,
                                    'student_id' => $request->student_id[$key],
                                    'number_of_meeting' => $schedule->meeting_number,
                                    'date' => Carbon::now(),
                                    'status' => $request->{'meeting_' . $schedule->meeting_number}[$key]
                                ];
                                $collections[] = $temp;
                            }
                        }
                    }
                }

                Presence::insert($collections);
                DB::commit();

                return $this->successResponse('Berhasil memperbarui data');
            } else {
                return response()->json([
                    'message' => 'Data presensi tidak ada'
                ], 500);
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
