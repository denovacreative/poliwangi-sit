<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CollegeClass\TeachingLecturerRequest;
use App\Models\CollegeClass;
use App\Models\TeachingLecturer;
use App\Models\WeeklySchedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class TeachingLecturerController extends Controller
{
    public function index(CollegeClass $collegeClass)
    {
        $query = TeachingLecturer::where('college_class_id', $collegeClass->id)
            ->with(['employee', 'weeklySchedule.day', 'weeklySchedule.room', 'collegeClass']);
        return DataTables::of($query)->addColumn('schedule', function ($data) {
            if ($data->weekly_schedule_id == null) {
                return '-';
            }
            return $data->weeklySchedule->day->name . ' - ' . Carbon::parse($data->weeklySchedule->time_start)->isoFormat('HH:mm') . ' s/d ' . Carbon::parse($data->weeklySchedule->time_end)->isoFormat('HH:mm') . ' (' . $data->weeklySchedule->room->name . ')';
        })->make();
    }

    public function store(CollegeClass $collegeClass, TeachingLecturerRequest $request)
    {
        try {

            $validate = $this->validateTeachingLecturer($collegeClass, $request);
            if ($validate['status'] == false) {
                return $this->errorResponse(500, $validate['message']);
            }

            if ($request->credit > $collegeClass->credit_total) {
                return $this->errorResponse(500, 'Jumlah sks melebihi sks matakuliah');
            }

            TeachingLecturer::create([
                'id' => Uuid::uuid4(),
                'evaluation_type_id' => $request->evaluation,
                'employee_id' => $request->lecturer,
                'weekly_schedule_id' => $request->weekly_schedule,
                'college_class_id' => $collegeClass->id,
                'credit_total' => $request->credit,
                'credit_meeting' => $request->credit,
                'credit_practicum' => 0,
                'credit_practice' => 0,
                'credit_simulation' => 0,
                'meeting_plan' => $request->meeting_plan,
                'meeting_realization' => $request->meeting_realization,
                'is_score_entry' => false,
            ]);
            return $this->successResponse('Berhasil menambahkan dosen ajar');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function update(CollegeClass $collegeClass, TeachingLecturer $teachingLecturer, TeachingLecturerRequest $request)
    {
        try {
            if ($request->lecturer != $teachingLecturer->employee_id) {
                $validate = $this->validateTeachingLecturer($collegeClass, $request, 'update');
                if ($validate['status'] == false) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }

            if ($request->credit > $collegeClass->credit_total) {
                return $this->errorResponse(500, 'Jumlah sks melebihi sks matakuliah');
            }

            $teachingLecturer->update([
                'evaluation_type_id' => $request->evaluation,
                'employee_id' => $request->lecturer,
                'weekly_schedule_id' => $request->weekly_schedule,
                'college_class_id' => $collegeClass->id,
                'credit_total' => $request->credit,
                'credit_meeting' => $request->credit,
                'credit_practicum' => 0,
                'credit_practice' => 0,
                'credit_simulation' => 0,
                'meeting_plan' => $request->meeting_plan,
                'meeting_realization' => $request->meeting_realization,
            ]);
            return $this->successResponse('Berhasil merubah dosen ajar');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function show(CollegeClass $collegeClass, TeachingLecturer $teachingLecturer)
    {
        try {
            return $this->successResponse('Berhasil mengambil data dosen ajar', compact('teachingLecturer'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(CollegeClass $collegeClass, TeachingLecturer $teachingLecturer)
    {
        try {
            $teachingLecturer->delete();
            return $this->successResponse('Berhasil menghapus dosen ajar');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function calculateCredit(CollegeClass $collegeClass)
    {
        try {
            if (count($collegeClass->teachingLecturer) <= 0) {
                return $this->errorResponse(500, 'Tidak dapat menghitung sks dosen! Dosen ajar masih kosong');
            }
            $countAllMeetingPlan = 0;
            foreach ($collegeClass->teachingLecturer as $key => $value) {
                $countAllMeetingPlan += $value->meeting_plan;
            }

            foreach ($collegeClass->teachingLecturer as $idx => $item) {
                $creditLecture = ($item->meeting_plan / $countAllMeetingPlan) * $collegeClass->credit_total;
                TeachingLecturer::where('id', $item->id)->update(['credit_total' => substr($creditLecture, 0, 4), 'credit_meeting' => substr($creditLecture, 0, 4)]);
            }
            return $this->successResponse('Berhasil menghitung sks dosen');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updateScore(CollegeClass $collegeClass, TeachingLecturer $teachingLecturer)
    {
        try {
            TeachingLecturer::where('id', '!=', $teachingLecturer->id)->update(['is_score_entry' => false]);
            $teachingLecturer->update(['is_score_entry' => !$teachingLecturer->is_score_entry]);
            $teachingLecturerCheck = TeachingLecturer::where('is_score_entry', true)->first();
            if (!$teachingLecturerCheck) {
                $teachingLecturer->update(['is_score_entry' => true]);
                return $this->errorResponse(500, 'Opps! Minimal harus ada satu dosen penginput nilai');
            }
            return $this->successResponse('Berhasil mengubah akses input nilai');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    private function validateTeachingLecturer($collegeClass, $request, $type = 'create')
    {
        // Cek dosen yang sama
        // $checkDuplicateLecturer = TeachingLecturer::where(['college_class_id' => $collegeClass->id, 'employee_id' => $request->lecturer])->first();
        // if ($checkDuplicateLecturer) {
        //     $data['status'] = false;
        //     $data['message'] = 'Dosen tersebut sudah menjadi dosen pengajar di kelas ini';
        //     return $data;
        // }
        // Cek jadwal dosen
        $currentSchedule = WeeklySchedule::where(['id' => $request->weekly_schedule])->first();
        $currentScheduleTimeStart = Carbon::parse($currentSchedule->time_start)->isoFormat('HH:mm');
        $currentScheduleTimeEnd = Carbon::parse($currentSchedule->time_end)->isoFormat('HH:mm');
        $teachingLecturers = TeachingLecturer::where(['employee_id' => $request->lecturer])->with('weeklySchedule')
            ->whereHas('collegeClass', function ($q) use ($collegeClass) {
                $q->where('academic_period_id', $collegeClass->academic_period_id);
                $q->where('id', '!=', $collegeClass->id);
            })
            ->whereNotNull('weekly_schedule_id')->get();

        foreach ($teachingLecturers as $key => $value) {
            $findSchedule = WeeklySchedule::where(['id' => $value->weekly_schedule_id])->first();
            $timeStart = Carbon::parse($findSchedule->time_start)->isoFormat('HH:mm');
            $timeEnd = Carbon::parse($findSchedule->time_end)->isoFormat('HH:mm');
            if ($currentSchedule->day_id == $findSchedule->day_id) {
                if ($currentScheduleTimeStart >= $timeStart && $currentScheduleTimeStart <= $timeEnd) {
                    $data['status'] = false;
                    $data['message'] = 'Dosen tersebut sudah mempunyai jadwal di kelas lain';
                    return $data;
                }
                if ($currentScheduleTimeEnd >= $timeStart && $currentScheduleTimeEnd <= $timeEnd) {
                    $data['status'] = false;
                    $data['message'] = 'Dosen tersebut sudah mempunyai jadwal di kelas lain';
                    return $data;
                }
            }
        }

        // Cek dosen yang berbeda dalam 1 waktu dan ruangan
        if ($type == 'create') {
            $checkDuplicateSchedule = TeachingLecturer::where(['college_class_id' => $collegeClass->id, 'weekly_schedule_id' => $request->weekly_schedule])->first();
            if ($checkDuplicateSchedule) {
                $data['status'] = false;
                $data['message'] = 'Sudah ada dosen yang mengajar di jam tersebut';
                return $data;
            }
        }

        return ['status' => true];
    }
}
