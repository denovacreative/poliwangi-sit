<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CollegeClass\WeeklyScheduleRequest;
use App\Models\CollegeClass;
use App\Models\WeeklySchedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class WeeklyScheduleController extends Controller
{
    public function index(CollegeClass $collegeClass)
    {
        try {
            // $weeklySchedules = WeeklySchedule::where('college_class_id', $collegeClass->id)->with(['collegeClass', 'day', 'meetingType', 'room'])->get();
            // return $this->successResponse(null, compact('weeklySchedules'));
            return DataTables::of(WeeklySchedule::where('college_class_id', $collegeClass->id)->with(['collegeClass', 'day', 'meetingType', 'room']))
                // ->addColumn('formatted_date', function($data) {
                //     return $data->date ? idDay(date('N', strtotime($data->date))) . ', ' . date('d-m-Y', strtotime($data->date)) : '-';
                // })
                ->addColumn('formatted_time', function ($data) {
                    return date('H:i', strtotime($data->time_start)) . ' - ' . date('H:i', strtotime($data->time_end));
                })
                ->make(true);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function store(CollegeClass $collegeClass, WeeklyScheduleRequest $request)
    {
        try {
            if ($request->has('room') and $request->room != '') {
                $checkRoom = WeeklySchedule::whereHas('collegeClass', function ($q) use ($request, $collegeClass) {
                    $q->where(['room_id' => $request->room, 'academic_period_id' => $collegeClass->academic_period_id]);
                    $q->where('id', '!=', $collegeClass->id);
                })->whereBetween('time_start', [$request->time_start, $request->time_end])->whereBetween('time_end', [$request->time_start, $request->time_end])->where('day_id', $request->day)->count();

                if ($checkRoom > 0) {
                    return response()->json([
                        'message' => 'Data jadwal mingguan dengan ruangan, hari, waktu mulai, dan waktu berakhir tersebut sudah ada'
                    ], 500);
                } else {
                    $checkDay = WeeklySchedule::where(['day_id' => $request->day, 'college_class_id' => $collegeClass->id])->count();
                    if ($checkDay > 0) {
                        return response()->json([
                            'message' => 'Data jadwal mingguan dengan hari tersebut sudah ada'
                        ], 500);
                    } else {
                        WeeklySchedule::create([
                            'id' => Uuid::uuid4(),
                            'college_class_id' => $collegeClass->id,
                            'day_id' => $request->day,
                            'meeting_type_id' => $request->meeting_type,
                            'room_id' => $request->room,
                            'time_start' => $request->time_start,
                            'time_end' => $request->time_end,
                            'learning_method' => $request->learning_method,
                            'created_at' => Carbon::now()
                        ]);
                    }
                }
            } else {
                $checkDay = WeeklySchedule::where(['day_id' => $request->day, 'college_class_id' => $collegeClass->id])->count();
                if ($checkDay > 0) {
                    return response()->json([
                        'message' => 'Data jadwal mingguan dengan hari tersebut sudah ada'
                    ], 500);
                } else {
                    WeeklySchedule::create([
                        'id' => Uuid::uuid4(),
                        'college_class_id' => $collegeClass->id,
                        'day_id' => $request->day,
                        'meeting_type_id' => $request->meeting_type,
                        'time_start' => $request->time_start,
                        'time_end' => $request->time_end,
                        'learning_method' => $request->learning_method,
                        'created_at' => Carbon::now()
                    ]);
                }
            }

            return $this->successResponse('Berhasil membuat data jadwal mingguan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(CollegeClass $collegeClass, WeeklySchedule $weeklySchedule)
    {
        $weeklySchedule->day;
        $weeklySchedule->meetingType;
        $weeklySchedule->room;
        return $this->successResponse(null, compact('weeklySchedule'));
    }

    public function update(CollegeClass $collegeClass, WeeklySchedule $weeklySchedule, WeeklyScheduleRequest $request)
    {
        try {
            if ($request->has('room') and $request->room != '') {
                $checkRoom = WeeklySchedule::whereHas('collegeClass', function ($q) use ($request, $collegeClass) {
                    $q->where(['room_id' => $request->room, 'academic_period_id' => $collegeClass->academic_period_id]);
                    $q->where('id', '!=', $collegeClass->id);
                })->whereBetween('time_start', [$request->time_start, $request->time_end])->whereBetween('time_end', [$request->time_start, $request->time_end])->where('day_id', $request->day)->where('id', '!=', $weeklySchedule->id)->count();

                if ($checkRoom > 0) {
                    return response()->json([
                        'message' => 'Data jadwal mingguan dengan ruangan, waktu mulai, dan waktu berakhir tersebut sudah ada'
                    ], 500);
                } else {
                    $checkDay = WeeklySchedule::where(['day_id' => $request->day, 'college_class_id' => $collegeClass->id])->where('id', '!=', $weeklySchedule->id)->count();
                    if ($checkDay > 0) {
                        return response()->json([
                            'message' => 'Data jadwal mingguan dengan hari tersebut sudah ada'
                        ], 500);
                    } else {
                        $weeklySchedule->update([
                            'college_class_id' => $collegeClass->id,
                            'day_id' => $request->day,
                            'meeting_type_id' => $request->meeting_type,
                            'room_id' => $request->room,
                            'time_start' => $request->time_start,
                            'time_end' => $request->time_end,
                            'learning_method' => $request->learning_method,
                        ]);
                    }
                }
            } else {
                $checkDay = WeeklySchedule::where(['day_id' => $request->day, 'college_class_id' => $collegeClass->id])->where('id', '!=', $weeklySchedule->id)->count();
                if ($checkDay > 0) {
                    return response()->json([
                        'message' => 'Data jadwal mingguan dengan hari tersebut sudah ada'
                    ], 500);
                } else {
                    $weeklySchedule->update([
                        'college_class_id' => $collegeClass->id,
                        'day_id' => $request->day,
                        'meeting_type_id' => $request->meeting_type,
                        'time_start' => $request->time_start,
                        'time_end' => $request->time_end,
                        'learning_method' => $request->learning_method,
                    ]);
                }
            }

            return $this->successResponse('Berhasil memperbarui data jadwal mingguan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(CollegeClass $collegeClass, WeeklySchedule $weeklySchedule)
    {
        try {
            $weeklySchedule->delete();

            return $this->successResponse('Berhasil menghapus data jadwal mingguan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
