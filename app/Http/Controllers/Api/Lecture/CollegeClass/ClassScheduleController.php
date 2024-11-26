<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CollegeClass\ClassScheduleRequest;
use App\Models\AcademicPeriod;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Presence;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class ClassScheduleController extends Controller
{

    public function index(CollegeClass $collegeClass)
    {
        return DataTables::of(ClassSchedule::where(['college_class_id' => $collegeClass->id])->with(['room', 'employee', 'meetingType', 'collegeClass'])->orderBy('meeting_number', 'asc'))
            ->addColumn('formatted_date', function ($data) {
                return $data->date ? idDay(date('N', strtotime($data->date))) . ', ' . date('d-m-Y', strtotime($data->date)) : '-';
            })
            ->addColumn('formatted_time_range', function ($data) {
                return date('H:i', strtotime($data->time_start)) . ' - ' . date('H:i', strtotime($data->time_end));
            })
            ->make();
    }

    public function show(CollegeClass $collegeClass, ClassSchedule $classSchedule)
    {
        $classSchedule->hashed_meeting_type_id = Hashids::encode($classSchedule->meeting_type_id);
        $classSchedule->hashed_room_id = Hashids::encode($classSchedule->room_id);
        return $this->successResponse(null, compact('classSchedule'));
    }

    public function update(CollegeClass $collegeClass, ClassSchedule $classSchedule, ClassScheduleRequest $request)
    {

        foreach ($request->files as $key => $file) {

            $rKey = explode('-', $key)[0];
            $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();

            if ($classSchedule[$rKey] && file_exists(public_path('storage/documents/class-schedules/' . $classSchedule[$rKey]))) {
                unlink(public_path('storage/documents/class-schedules/' . $classSchedule[$rKey]));
            }

            $file->move(public_path('storage/documents/class-schedules'), $randomName);
            $request->merge([
                $rKey => $randomName
            ]);
        }

        if ($request->has('meeting_type_id') && $request->meeting_type_id != '') {
            $request->merge(['meeting_type_id' => Hashids::decode($request->meeting_type_id)[0]]);
        }

        if ($request->has('room_id') && $request->room_id != '') {
            $request->merge(['room_id' => Hashids::decode($request->room_id)[0]]);
        }

        $classSchedule->update($request->only([
            'meeting_number', 'credit', 'employee_id', 'date', 'time_start', 'time_end', 'meeting_type_id', 'learning_method', 'room_id', 'status', 'credit', 'location', 'attachment', 'presence_document', 'journal_document', 'material_plan', 'material_realization'
        ]));

        return $this->successResponse('Berhasil mengupdate jadwal perkuliahan');
    }

    public function store(CollegeClass $collegeClass, ClassScheduleRequest $request)
    {
        try {

            DB::beginTransaction();

            $request->merge(['id' => Uuid::uuid4()]);

            $request->merge(['college_class_id' => $collegeClass->id]);

            if ($request->has('meeting_type_id') && $request->meeting_type_id != '') {
                $request->merge(['meeting_type_id' => Hashids::decode($request->meeting_type_id)[0]]);
            }

            if ($request->has('room_id') && $request->room_id != '') {
                $request->merge(['room_id' => Hashids::decode($request->room_id)[0]]);
            }

            // Upload files
            foreach ($request->files as $key => $file) {
                $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/documents/class-schedules'), $randomName);
                $request->merge([
                    (explode('-', $key)[0]) => $randomName
                ]);
            }

            $cs = ClassSchedule::create($request->only([
                'id', 'meeting_number', 'college_class_id', 'credit', 'employee_id', 'date', 'time_start', 'time_end', 'meeting_type_id', 'learning_method', 'room_id', 'status', 'credit', 'location', 'attachment', 'presence_document', 'journal_document', 'material_plan', 'material_realization'
            ]));

            // $presencesToInsert = [];

            // foreach ($collegeClass->classParticipant as $cp) {
            //     $presencesToInsert[] = [
            //         'college_class_id' => $collegeClass->id,
            //         'class_schedule_id' => $cs->id,
            //         'student_id' => $cp->student_id,
            //         'number_of_meeting' => $cs->meeting_number,
            //         'date' => $cs->date,
            //         'status' => '0',
            //         'created_at' => Carbon::now(),
            //     ];
            // }

            // Presence::insert($presencesToInsert);

            DB::commit();

            return $this->successResponse('Berhasil membuat data jadwal perkuliahan');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }

    public function generateData(CollegeClass $collegeClass)
    {
        try {

            DB::beginTransaction();

            if ($collegeClass->classSchedule()->whereIn('status', ['start', 'done', 'reschedule'])->count()) {
                throw new Exception('Terdapat kelas yang sudah dimulai, tidak dapat melakukan generate data !');
            }

            $collegeClass->classSchedule()->delete();

            $numberOfMeeting = $collegeClass->number_of_meeting;

            $classSchedulesToInsert = [];

            $weeklySchedules = $collegeClass->weeklySchedule()->orderBy('day_id', 'ASC')->get();

            if (!$collegeClass->date_start) throw new Exception("Tanggal mulai kelas belum di set di kelas ini!");

            if (!$weeklySchedules || $weeklySchedules->count() <= 0 || $weeklySchedules == []) {
                throw new Exception('Tidak ada jadwal mingguan di kelas ini !');
            }

            $weeksTotal = ceil($numberOfMeeting / $weeklySchedules->count());

            $startingPoint = Carbon::parse($collegeClass->date_start)->subDay($this->countDateDifference(Carbon::now()->dayOfWeek, $weeklySchedules[0]->day_id));

            // $startingPoint = Carbon::parse(AcademicPeriod::where('is_use', true)->first()->college_start_date)->subDay($this->countDateDifference(Carbon::now()->dayOfWeek, $weeklySchedules[0]->day_id));

            $y = 0;

            for ($i = 0; $i < $weeksTotal; $i++) {

                foreach ($weeklySchedules as $ws) {

                    if (!isset($ws->teachingLecturer[0])) {
                        throw new Exception('Tidak ada dosen ajar di hari ' . $ws->day->name);
                    }

                    if (++$y <= $numberOfMeeting) {

                        $classScheduleId = Uuid::uuid4();
                        $date = Carbon::parse($startingPoint)->startOfWeek()->subWeek(-$i)->subDay((-$ws->day_id) + 1);

                        $classSchedulesToInsert[] = [
                            'id' => $classScheduleId,
                            'employee_id' => $ws->teachingLecturer[0]->employee_id,
                            'room_id' => $ws->room_id,
                            'meeting_type_id' => $ws->meeting_type_id,
                            'college_class_id' => $collegeClass->id,
                            'meeting_number' => $y,
                            'time_start' => $ws->time_start,
                            'time_end' => $ws->time_end,
                            'date' => $date,
                            'learning_method' => $ws->learning_method,
                            'credit' => $collegeClass->credit_total,
                            'status' => 'schedule',
                            'created_at' => Carbon::now(),
                        ];
                    } else {
                        break;
                    }
                }
            }
            ClassSchedule::insert($classSchedulesToInsert);
            DB::commit();
            return $this->successResponse('Berhasil me-generate data jadwal perkuliahan !');
        } catch (Exception $e) {
            DB::rollback();
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(CollegeClass $collegeClass, ClassSchedule $classSchedule)
    {
        try {
            DB::beginTransaction();

            $files = ['attachment', 'presence_document', 'journal_document'];

            foreach ($files as $file) {
                if ($classSchedule[$file] && file_exists(public_path('storage/documents/class-schedules/' . $classSchedule[$file]))) {
                    unlink(public_path('storage/documents/class-schedules/' . $classSchedule[$file]));
                }
            }

            // $classSchedule->presence()->delete();
            $classSchedule->delete();
            DB::commit();
            return $this->successResponse('Berhasil menghapus data jadwal perkuliahan!');
        } catch (Exception $e) {
            DB::rollback();
            return $this->exceptionResponse($e);
        }
    }

    public function fillRealization(CollegeClass $collegeClass, ClassSchedule $classSchedule, Request $request)
    {
        $request->validate([
            'material_plan' => 'required',
            'material_realization' => 'required'
        ]);
        try {
            $classSchedule->update([
                'material_plan' => $request->material_plan,
                'material_realization' => $request->material_realization
            ]);
            return $this->successResponse('Berhasil mengisi realisasi perkuliahan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    private function countDateDifference($x, $y)
    {
        return $y >= $x ? $x - $y : $x - $y - 8;
    }
}
