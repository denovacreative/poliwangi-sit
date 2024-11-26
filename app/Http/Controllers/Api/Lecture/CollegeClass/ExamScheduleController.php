<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CollegeClass\ExamScheduleRequest;
use App\Models\CollegeClass;
use App\Models\ExamSchedule;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class ExamScheduleController extends Controller
{
    public function index(CollegeClass $collegeClass)
    {
        $query = ExamSchedule::where('college_class_id', $collegeClass->id)->with(['employee1', 'employee2', 'room', 'meetingType', 'collegeClass.classParticipant']);
        return DataTables::of($query)->editColumn('date', function ($data) {
            return Carbon::parse($data->date)->isoFormat('dddd, D MMM Y');
        })->addColumn('time', function ($data) {
            return Carbon::parse($data->time_start)->isoFormat('HH:mm') . ' s/d ' . Carbon::parse($data->time_end)->isoFormat('HH:mm');
        })->editColumn('location', function ($data) {
            return $data->type == 'offline' ? $data->room->name . ' / ' . $data->location : $data->location;
        })->addColumn('participant_count', function ($data) {
            return count($data->collegeClass->classParticipant);
        })->make();
    }

    public function store(CollegeClass $collegeClass, ExamScheduleRequest $request)
    {
        try {
            $validate = $this->validateExamSchedule($collegeClass, $request, ['meeting_type', 'schedule', 'room', 'employee1', 'employee2']);
            if ($validate['status'] == false) {
                return $this->errorResponse(500, $validate['message']);
            }
            ExamSchedule::create([
                'id' => Uuid::uuid4(),
                'college_class_id' => $collegeClass->id,
                'meeting_type_id' => $request->meeting_type,
                'room_id' => $request->room,
                'employee_id_1' => $request->employee1,
                'employee_id_2' => $request->employee2,
                'type' => $request->type,
                'location' => $request->location,
                'date' => $request->date,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
            ]);
            return $this->successResponse('Berhasil menambahkan jadwal ujian');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function update(CollegeClass $collegeClass, ExamSchedule $examSchedule, ExamScheduleRequest $request)
    {
        try {
            if ($request->meeting_type != $examSchedule->meeting_type_id) {
                $validate = $this->validateExamSchedule($collegeClass, $request, ['meeting_type']);
                if ($validate['status'] == false) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->room != $examSchedule->room_id) {
                $validate = $this->validateExamSchedule($collegeClass, $request, ['room']);
                if ($validate['status'] == false) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->employee1 != $examSchedule->employee_id_1) {
                $validate = $this->validateExamSchedule($collegeClass, $request, ['employee1']);
                if ($validate['status'] == false) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->employee2 != $examSchedule->employee_id_2) {
                $validate = $this->validateExamSchedule($collegeClass, $request, ['employee2']);
                if ($validate['status'] == false) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->time_start != $examSchedule->time_start && $request->time_end != $examSchedule->time_end) {
                $validate = $this->validateExamSchedule($collegeClass, $request, ['schedule']);
                if ($validate['status'] == false) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            $examSchedule->update([
                'meeting_type_id' => $request->meeting_type,
                'room_id' => $request->room,
                'employee_id_1' => $request->employee1,
                'employee_id_2' => $request->employee2,
                'type' => $request->type,
                'location' => $request->location,
                'date' => $request->date,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
            ]);
            return $this->successResponse('Berhasil merubah jadwal ujian');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function show(CollegeClass $collegeClass, ExamSchedule $examSchedule)
    {
        try {
            return $this->successResponse('Berhasil mengambil data jadwal ujian', compact('examSchedule'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(CollegeClass $collegeClass, ExamSchedule $examSchedule)
    {
        try {
            $examSchedule->delete();
            return $this->successResponse('Berhasil menghapus dosen ajar');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    private function validateExamSchedule($collegeClass, $request, $validate = [])
    {
        $timeStartFromReq = Carbon::parse($request->time_start)->isoFormat('HH:mm');
        $timeEndFromReq = Carbon::parse($request->time_end)->isoFormat('HH:mm');
        // Cek pertemuan
        if (in_array('meeting_type', $validate)) {
            $findMeeting = ExamSchedule::where(['meeting_type_id' => $request->meeting_type, 'college_class_id' => $collegeClass->id])->get();
            if (count($findMeeting) > 0) {
                $data['status'] = false;
                $data['message'] = 'Jenis ujian sudah terjadwal';
                return $data;
            }
        }
        // Cek range jam
        $findTimeStart = TimeSlot::where(['time' => $request->time_start])->first();
        $findTimeEnd = TimeSlot::where(['time' => $request->time_end])->first();
        if ($findTimeStart == null || $findTimeEnd == null) {
            $data['status'] = false;
            $data['message'] = 'Waktu tersebut belum terdaftar';
            return $data;
        }

        // Check jadwal
        if (in_array('schedule', $validate)) {
            $checkSchedule = ExamSchedule::where(['date' => $request->date, 'college_class_id' => $collegeClass->id])->get();
            if (count($checkSchedule) > 0) {
                foreach ($checkSchedule->get() as $value) {
                    $timeStart = Carbon::parse($value->time_start)->isoFormat('HH:mm');
                    $timeEnd = Carbon::parse($value->time_end)->isoFormat('HH:mm');

                    if ($timeStartFromReq >= $timeStart && $timeStartFromReq <= $timeEnd) {
                        $data['status'] = false;
                        $data['message'] = 'Terdapat jadwal yang bentrok';
                        return $data;
                    }
                    if ($timeEndFromReq >= $timeStart && $timeEndFromReq <= $timeEnd) {
                        $data['status'] = false;
                        $data['message'] = 'Terdapat jadwal yang bentrok';
                        return $data;
                    }
                }
            }
        }
        if (in_array('room', $validate)) {
            // Cek ruangan
            $checkRoom = ExamSchedule::where(['date' => $request->date, 'room_id' => $request->room])->get();
            if (count($checkRoom) > 0) {
                foreach ($checkRoom as $room) {
                    $tRoomStart = Carbon::parse($room->time_start)->isoFormat('HH:mm');
                    $tRoomEnd = Carbon::parse($room->time_end)->isoFormat('HH:mm');

                    if ($timeStartFromReq >= $tRoomStart && $timeStartFromReq <= $tRoomEnd) {
                        $data['status'] = false;
                        $data['message'] = 'Ruangan tersebut sudah digunakan';
                        return $data;
                    }
                    if ($timeEndFromReq >= $tRoomStart && $timeEndFromReq <= $tRoomEnd) {
                        $data['status'] = false;
                        $data['message'] = 'Ruangan tersebut sudah digunakan';
                        return $data;
                    }
                }
            }
        }
        if ($request->employee1 == $request->employee2) {
            $data['status'] = false;
            $data['message'] = 'Pengawas 1 dan 2 tidak boleh sama';
            return $data;
        }
        // Cek pengawas
        if (in_array('employee1', $validate)) {
            $checkEmployee1 = ExamSchedule::where(['date' => $request->date, 'employee_id_1' => $request->employee1])->get();
            if (count($checkEmployee1) > 0) {
                foreach ($checkEmployee1 as $employee1) {
                    $tEmployee1Start = Carbon::parse($employee1->time_start)->isoFormat('HH:mm');
                    $tEmployee1End = Carbon::parse($employee1->time_end)->isoFormat('HH:mm');

                    if ($timeStartFromReq >= $tEmployee1Start && $timeStartFromReq <= $tEmployee1End) {
                        $data['status'] = false;
                        $data['message'] = 'Pengawas 1 sudah memiliki jadwal di waktu tersebut';
                        return $data;
                    }
                    if ($timeEndFromReq >= $tEmployee1Start && $timeEndFromReq <= $tEmployee1End) {
                        $data['status'] = false;
                        $data['message'] = 'Pengawas 1 sudah memiliki jadwal di waktu tersebut';
                        return $data;
                    }
                }
            }
        }
        if (in_array('employee2', $validate)) {
            $checkEmployee2 = ExamSchedule::where(['date' => $request->date, 'employee_id_2' => $request->employee2])->get();
            if (count($checkEmployee2) > 0) {
                foreach ($checkEmployee2 as $employee2) {
                    $tEmployee2Start = Carbon::parse($employee2->time_start)->isoFormat('HH:mm');
                    $tEmployee2End = Carbon::parse($employee2->time_end)->isoFormat('HH:mm');

                    if ($timeStartFromReq >= $tEmployee2Start && $timeStartFromReq <= $tEmployee2End) {
                        $data['status'] = false;
                        $data['message'] = 'Pengawas 2 sudah memiliki jadwal di waktu tersebut';
                        return $data;
                    }
                    if ($timeEndFromReq >= $tEmployee2Start && $timeEndFromReq <= $tEmployee2End) {
                        $data['status'] = false;
                        $data['message'] = 'Pengawas 2 sudah memiliki jadwal di waktu tersebut';
                        return $data;
                    }
                }
            }
        }

        return ['status' => true];
    }
}
