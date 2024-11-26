<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Presence;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ClassAttendanceController extends Controller
{
    public function index(CollegeClass $collegeClass, Request $request)
    {
        try {
            $classParticipants = ClassParticipant::where(['college_class_id' => $collegeClass->id])->with(['student' => function ($q) use ($request) {
                $q->with(['presence' => function ($q) use ($request) {
                    $q->where('class_schedule_id', $request->class_schedule);
                }]);
            }])->get();
            $classSchedule = $request->class_schedule;

            return $this->successResponse(null, compact('classParticipants', 'classSchedule'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updatePresence(CollegeClass $collegeClass, Request $request)
    {
        try {
            if ($request->student > 0) {
                $classSchedule = ClassSchedule::find($request->class_schedule);
                if ($classSchedule->status == 'done' && !is_null($request->class_schedule)) {
                    $batchInsert = [];

                    foreach ($request->student as $key => $value) {
                        $checkPresence = Presence::where(['class_schedule_id' => $classSchedule->id, 'student_id' => $value]);
                        if ($checkPresence->count() > 0) {
                            $checkPresence->update([
                                // 'date' => date('Y-m-d'),
                                'status' => $request->status[$key]
                            ]);
                        } else {
                            $batchInsert[] = [
                                'college_class_id' => $collegeClass->id,
                                'class_schedule_id' => $classSchedule->id,
                                'student_id' => $value,
                                'number_of_meeting' => $classSchedule->meeting_number,
                                'date' => date('Y-m-d'),
                                'status' => $request->status[$key],
                                'created_at' => Carbon::now()
                            ];
                        }
                    }
                    Presence::insert($batchInsert);

                    return $this->successResponse('Berhasil menyimpan data presensi kelas');
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Opps! status kelas masih belum selesai'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Opps! data mahasiswa tidak ada'
                ], 500);
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
