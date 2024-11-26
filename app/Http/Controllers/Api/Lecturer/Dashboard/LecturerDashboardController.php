<?php

namespace App\Http\Controllers\Api\Lecturer\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Guardianship;
use App\Models\Score;
use App\Models\Student;
use App\Models\TeachingLecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Exception;
use PHPUnit\Framework\MockObject\Stub\Exception as StubException;
use Vinkla\Hashids\Facades\Hashids;

class LecturerDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'lecturerCreditTotal' => $this->getLecturerCreditTotal(),
            'studentTotal' => Student::where('employee_id', getInfoLogin()->userable->id)->count(),
            'unrealizedTotal' => $this->getUnrealizedClass(false),
            'unrealizedLists' => $this->getUnrealizedClass(),
            'unlockScoreTotal' => $this->getUnlockScore(false),
            'unpresenceTotal' => $this->getUnpresence(false),
            'unpresenceLists' => $this->getUnpresence(),
            'unlockScoreLists' => $this->getUnlockScore(),
            'unguardianshipTotal' => $this->getUnguardianship(),
            'announcements' => $this->getAnnouncements(),
            'collegeClassTotal' => $this->getCollegeClassTotal(),
        ];

        return $this->successResponse(null, $data);
    }

    private function getLecturerCreditTotal()
    {
        $creditTotals = Auth::user()->userable->teachingLecturer()->whereHas('collegeClass', function ($q) {
            $q->whereHas('academicPeriod', function ($cc) {
                $cc->where('is_use', true);
            });
        })->get(['credit_total'])->pluck('credit_total')->sum();

        return $creditTotals;
    }

    private function getUnrealizedClass($instantGet = true)
    {
        $query = CollegeClass::whereHas('teachingLecturer', function ($query) {
            $query->where('employee_id', getInfoLogin()->userable->id);
        })->whereHas('classSchedule', function ($q) {
            $q->where('status', 'done');
            $q->whereNull('material_realization');
        })->with(['classSchedule', 'course'])->get();
        $data = [];
        foreach ($query as $i => $cc) {
            $count = 0;
            foreach ($cc->classSchedule as $j => $cs) {
                if ($cs->status == 'done' && $cs->material_realization == null) {
                    $count++;
                }
            }
            $data[] = [
                'college_class_id' => $cc->id,
                'course_id' => $cc->course_id,
                'course_name' => $cc->course->name,
                'class_name' => $cc->name,
                'count' => $count
            ];
        }
        return $instantGet == false ? $query->count() : response()->json(['data' => $data]);
    }

    private function getUnlockScore($instantGet = true)
    {
        $query = CollegeClass::whereHas('teachingLecturer', function ($q) {
            $q->where('employee_id', getInfoLogin()->userable->id);
        })->withCount(['score'])->where(['is_lock_score' => false])->get();
        $count = 0;
        $data = [];
        foreach ($query as $key => $value) {
            if ($value->score_count > 0) {
                $count++;
                $data[] = [
                    'college_class_id' => $value->id,
                    'course_id' => $value->course_id,
                    'course_name' => $value->course->name,
                    'class_name' => $value->name,
                ];
            }
        }
        return $instantGet == false ? $count : response()->json(['data' => $data]);
    }

    public function getUnpresence($instantGet = true)
    {
        $query = CollegeClass::whereHas('teachingLecturer', function ($q) {
            $q->where('employee_id', getInfoLogin()->userable->id);
        })->whereHas('classSchedule', function ($q) {
            $q->where('status', 'done');
        })->with(['classSchedule.presence', 'course']);

        $data = [];

        foreach ($query->get() as $i => $cc) {
            $count = 0;
            foreach ($cc->classSchedule as $j => $cs) {
                if ($cs->status == 'done') {
                    if (count($cs->presence) > 0) {
                        if ($cs->presence[0]->status == "0") {
                            $count++;
                        }
                    } else {
                        $count++;
                    }
                }
            }
            $data[] = [
                'college_class_id' => $cc->id,
                'course_id' => $cc->course_id,
                'course_name' => $cc->course->name,
                'class_name' => $cc->name,
                'count' => $count
            ];
        }
        return $instantGet == false ? $query->count() : response()->json(['data' => $data]);
    }

    private function getUnguardianship()
    {
        $query = Student::where(['employee_id' => getInfoLogin()->userable->id, 'student_status_id' => 'A'])->get();
        $count = 0;
        foreach ($query as $key => $value) {
            $getGuardianship = Guardianship::where(['student_id' => $value->id, 'employee_id' => getInfoLogin()->userable->id, 'academic_period_id' => getActiveAcademicPeriod(true)->id])->first();
            if (!$getGuardianship) {
                $count++;
            }
        }
        return $count;
    }

    private function getAnnouncements()
    {
        $announcements = Announcement::where('is_active', true)->where(function ($q) {
            $q->where('user_id', Auth::user()->id)->orWhere('role_id', Auth::user()->roles()->get()[0]->id);
        })->orderBy('is_priority', 'DESC')->orderBy('created_at', 'DESC')->take(5)->get();

        return compact('announcements');
    }

    private function getCollegeClassTotal()
    {
        $collegeClassTotal = getInfoLogin()->userable->teachingLecturer()->whereHas('collegeClass', function ($q) {
            $q->where('academic_period_id', getActiveAcademicPeriod()->id);
        })->count();

        return $collegeClassTotal;
    }

    public function getClassSchedule(Request $request)
    {
        $newDate = Carbon::createFromFormat('D, d/m/Y', $request->date)->format('Y-m-d');
        $classSchedules = ClassSchedule::with(['room', 'collegeClass.course', 'meetingType'])->whereEmployeeId(getInfoLogin()->userable_id)->where('date', $newDate)->get();

        $classSchedules = $classSchedules->map(function($data) {
            $data->time_start_display = date('H:i', strtotime($data->time_start));
            $data->time_end_display = date('H:i', strtotime($data->time_end));
            $data->date_display = date('D, d M Y', strtotime($data->date));

            return $data;
        });

        return $this->successResponse(null, compact('classSchedules'));
    }

    public function realizationClassSchedule(Request $request){
        try{

            $schedule = ClassSchedule::where('id', $request->id)->first();

            ClassSchedule::where('id', $request->id)->update([
                'material_plan' => $request->material_plan,
                'material_realization' => $request->material_realization,
                'status' => 'done',
            ]);

            return $this->successResponse('Data telah di realisasikan');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }

    }

    public function classRescheduling(Request $request){
        try{
            if(
                $request->employee_id == '' ||
                $request->date == '' ||
                $request->time_start == '' ||
                $request->time_end == '' ||
                $request->meeting_type_id == '' ||
                $request->learning_method == '' ||
                $request->room_id == '' ||
                $request->location == ''
            ){
                return response()->json([
                    'message' => 'Semua field harus diisi',
                ], 500);
            }


            $schedule = ClassSchedule::where('id', $request->schedule_id)->first();

            $classScheduleId = Uuid::uuid4();

            $daat = [
                'id' => $classScheduleId,
                'employee_id' => $request->employee_id,
                'room_id' => Hashids::decode($request->room_id)[0],
                'meeting_type_id' => Hashids::decode($request->meeting_type_id)[0],
                'college_class_id' => $schedule->college_class_id,
                'meeting_number' => $schedule->meeting_number,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
                'date' => $request->date,
                'learning_method' => $request->learning_method,
                'credit' => $schedule->credit,
                'status' => 'schedule',
                'created_at' => Carbon::now(),
            ];

            ClassSchedule::create($daat);

            ClassSchedule::where('id', $request->schedule_id)->update(['status' => 'reschedule']);

            return $this->successResponse('Data telah terjadwal ulang');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function startClassSchedules(ClassSchedule $classSchedule, Request $request){

        try{

            if(
                $classSchedule->date == Date('Y-m-d') ||
                $classSchedule->time_start <= date('H:i:s') ||
                $classSchedule->time_end >= date('H:i:s')
            ){

                $classSchedule->update(['status' => 'start']);

                return $this->successResponse('Kelas telah dimulai');

            }else{

                return response()->json([
                    'message' => 'Hanya dapat dimulai sesuai jadwal yang tertera',
                ], 500);

            }


        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }

    }
}
