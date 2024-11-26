<?php

namespace App\Http\Controllers\Api\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Presence;
use Exception;
use Illuminate\Http\Request;
use stdClass;
use Yajra\DataTables\DataTables;

class StudentPresenceController extends Controller
{
    public function index(Request $request)
    {
        $query = CollegeClass::whereHas('classParticipant', function ($q) {
            $q->where('student_id', getInfoLogin()->userable->id);
        })->with(['course', 'academicPeriod', 'teachingLecturer.employee'])->with(['presence' => function ($q) {
            $q->where('student_id', getInfoLogin()->userable->id);
        }]);

        if ($request->has('academic_period_id') and $request->academic_period_id != '') {
            $query->where('academic_period_id', $request->academic_period_id);
        } else {
            $query->where('academic_period_id', getActiveAcademicPeriod()->id);
        }


        return DataTables::of($query->get())
            ->addColumn('presence_count', function ($data) {
                return !is_null($data->presence) ? $data->presence->where('status', 'H')->pluck('status')->count() : 0;
            })
            ->addColumn('not_presence_count', function ($data) {
                return !is_null($data->presence) ? $data->presence->where('status', 'A')->pluck('status')->count() : 0;
            })
            ->addColumn('attendance_presentage', function ($data) {
                $attendancePersentage = 0;
                $notPresenceCount = !is_null($data->presence) ? $data->presence->where('status', 'A')->pluck('status')->count() : 0;
                $classScheduleCount = $data->classSchedule->count();
                if ($classScheduleCount > 0) {
                    $attendancePersentage = $notPresenceCount / $classScheduleCount * 100;
                    $attendancePersentage = 100 - $attendancePersentage;
                }
                return $attendancePersentage . '%';
            })
            ->make();
    }

    public function detail(CollegeClass $collegeClass)
    {
        try {
            $classSchedule = ClassSchedule::where('college_class_id', $collegeClass->id)->orderBy('meeting_number', 'asc')->get();

            $headers = [];
            $collections = [];
            $temp = [];
            $presenceCount = 0;
            $notPresenceCount = 0;
            $classScheduleCount = count($classSchedule);
            $attendancePersentage = 0;

            $temp['name'] = getInfoLogin()->userable->nim . ' - ' . getInfoLogin()->userable->name;
            foreach ($classSchedule as $schedule) {
                $presence = Presence::where(['college_class_id' => $collegeClass->id, 'number_of_meeting' => $schedule->meeting_number, 'student_id' => getInfoLogin()->userable->id])->first();
                $presenceCount += !is_null($presence) ? ($presence->status == 'H' ? 1 : 0) : 0;
                $notPresenceCount += !is_null($presence) ? ($presence->status == 'A' ? 1 : 0) : 0;
                $temp['meeting_of_' . $schedule->meeting_number] = !is_null($presence) ? $presence->status : 0;
                $headers[] = $schedule->meeting_number;
            }
            $collections[] = $temp;
            if ($classScheduleCount > 0) {
                $attendancePersentage = $notPresenceCount / $classScheduleCount * 100;
                $attendancePersentage = 100 - $attendancePersentage;
            }

            $collegeClass->course;
            $collegeClass->academicPeriod;
            $collegeClass->teaching_lecture = $collegeClass->teachingLecturer()->with('employee')->get();
            $collegeClass->presence_count = $presenceCount;
            $collegeClass->not_presence_count = $notPresenceCount;
            $collegeClass->attendance_presentage = $attendancePersentage . '%';

            return $this->successResponse(null, ['collections' => $collections, 'headers' => $headers, 'college_class' => $collegeClass]);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
