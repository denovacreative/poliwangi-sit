<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\ClassScheduleRequest;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\Presence;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Ramsey\Uuid\Uuid;
use Vinkla\Hashids\Facades\Hashids;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassSchedule::with(['employee', 'room', 'meetingType', 'collegeClass.studyProgram.educationLevel', 'presence'])
            ->whereHas('collegeClass', function ($q) {
                $q->whereIn('academic_period_id', getActiveAcademicPeriod(false, true));
            });

        if (!is_null($request->study_program) and $request->study_program != '' and $request->study_program != 'all') {
            $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program);
            });
        }

        if (!is_null($request->status) and $request->status != '' and $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if (!is_null($request->date) and $request->date != '' and $request->date != 'all') {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::now());
        }

        return DataTables::of($query)
            ->addColumn('count_presence', function ($data) {
                return $data->presence->where('status', 'H')->count();
            })
            ->make();
    }

    public function presence(ClassSchedule $classSchedule)
    {
        $classParticipants = ClassParticipant::where('college_class_id', $classSchedule->college_class_id)->with(['student' => function ($q) use ($classSchedule) {
            $q->with(['presence' => function ($q) use ($classSchedule) {
                $q->where('class_schedule_id', $classSchedule->id);
            }]);
        }])->with([
            'collegeClass.academicPeriod', 'collegeClass.studyProgram.educationLevel', 'collegeClass.lectureSystem', 'collegeClass.course'
        ])->get();

        return $this->successResponse(null, compact('classParticipants'));
    }

    public function updatePresence(ClassSchedule $classSchedule, Request $request)
    {
        try {
            if ($classSchedule->status == 'done') {
                if ($request->student > 0) {
                    $batchInsert = [];
                    // $classSchedule->collegeClass;

                    foreach ($request->student as $key => $value) {
                        $checkPresence = Presence::where(['student_id' => $value, 'class_schedule_id' => $classSchedule->id]);
                        // $id;
                        if ($checkPresence->count() > 0) {
                            $checkPresence->update([
                                'date' => date('Y-m-d'),
                                'status' => $request->status[$key]
                            ]);
                        } else {
                            $batchInsert[] = [
                                // 'id' => $id ?? $lastPresence->id + 1,
                                'college_class_id' => $classSchedule->college_class_id,
                                'class_schedule_id' => $classSchedule->id,
                                'student_id' => $value,
                                'number_of_meeting' => $classSchedule->meeting_number,
                                'date' => date('Y-m-d'),
                                'status' => $request->status[$key],
                                'created_at' => Carbon::now()
                            ];
                        }
                    }

                    // Presence::where(['class_schedule_id' => $classSchedule->id, 'student_id' => $value])->update(['status' => $request->status[$key]]);
                    // return response()->json([
                    //     'data' => $batchInsert
                    // ], 500);
                    // Presence::upsert($batchInsert, ['id'], ['date', 'status']);
                    Presence::insert($batchInsert);

                    return $this->successResponse('Berhasil menyimpan data presensi mahasiswa');
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Opps! data mahasiswa tidak ada'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Opps! status kelas masih belum selesai'
                ], 500);
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        try {
            $classSchedule->delete();

            return $this->successResponse('Berhasil menghapus data jadwal kelas');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
