<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicPeriod;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\Student;
use App\Models\TeachingLecturer;
use App\Models\WeeklySchedule;
use App\Http\Requests\Lecture\CollegeClass\ScheduleRequest;
use Vinkla\Hashids\Facades\Hashids;
use DataTables;
use Carbon\Carbon;
use Exception;
use Ramsey\Uuid\Uuid;
use DB;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {
        $query = CollegeClass::query();
        if ($request->has('academic_period_id') && $request->academic_period_id != '') {
            $query = $query->whereAcademicPeriodId($request->academic_period_id);
        }
        if ($request->has('curriculum_id') && $request->curriculum_id != '') {
            $query = $query->whereCurriculumId($request->curriculum_id);
        }
        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query = $query->whereStudyProgramId($request->study_program_id);
        }
        if ($request->has('lecture_system_id') && $request->lecture_system_id != '') {
            $query = $query->whereLectureSystemId(Hashids::decode($request->lecture_system_id)[0]);
        }
        if ($request->has('is_lock') && $request->is_lock != '') {
            $query = $query->whereIsLockScore($request->is_lock);
        }
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }
        return DataTables::of($query->with(['academicPeriod', 'course', 'studyProgram' => function ($q) {
            $q->with(['educationLevel']);
        }, 'classParticipant', 'score'])->whereIn('academic_period_id', getActiveAcademicPeriod(false, true))->orderBy('created_at', 'desc'))
            ->addColumn('score_count', function ($data) {
                return count($data->score);
            })->addColumn('participant_count', function ($data) {
                return count($data->classParticipant);
            })->make();
    }

    public function store(ScheduleRequest $request)
    {
        try {
            DB::beginTransaction();
            $ids = explode('|', $request->course_id);
            $course = Course::whereId($ids[0])->first();
            $request->merge([
                'credit_total' => $course->credit_total == null ? 0 : $course->credit_total,
                'credit_meeting' => $course->credit_meeting == null ? 0 : $course->credit_meeting,
                'credit_practicum' => $course->credit_practicum == null ? 0 : $course->credit_practicum,
                'credit_practice' => $course->credit_practice == null ? 0 : $course->credit_practice,
                'credit_simulation' => $course->credit_simulation == null ? 0 : $course->credit_simulation,
                'lecture_system_id' => Hashids::decode($request->lecture_system_id)[0],
                'curriculum_id' => $ids[1],
                'course_id' => $ids[0],
                'id' => Uuid::uuid4()
            ]);

            foreach($request->class_name as $key => $class_name) {
                // insert college  class
                $request->merge(['name' => $class_name]);
                $class_group_id = Hashids::decode(explode('|', $request->class_group_ids)[$key])[0];
                $collegeClass = CollegeClass::create($request->only(['id', 'academic_period_id', 'study_program_id', 'course_id', 'curriculum_id', 'lecture_system_id', 'name', 'capacity', 'date_start', 'date_end', 'number_of_meeting', 'credit_total', 'credit_meeting', 'credit_practicum', 'credit_practice', 'credit_simulation', 'case_discussion']));
                $students = Student::where('class_group_id', $class_group_id)->get();
                $students = $students->map(function($item) use ($collegeClass) {
                    return [
                        'id' => Uuid::uuid4(),
                        'student_id' => $item->id,
                        'college_class_id' => $collegeClass->id,
                        'created_at' => Carbon::now()
                    ];
                });

                // insert class participant
                ClassParticipant::insert($students->toArray());

                // insert weekly schedule
                $weeklySchedule = WeeklySchedule::create([
                    'id' => Uuid::uuid4(),
                    'college_class_id' => $collegeClass->id,
                    'day_id' => $request->day_id[$key],
                    'meeting_type_id' => $request->meeting_type_id[$key],
                    'room_id' => $request->room_id[$key],
                    'time_start' => $request->time_start_id[$key],
                    'time_end' => $request->time_end_id[$key],
                    'learning_method' => $request->learning_method[$key],
                    'created_at' => Carbon::now()
                ]);

                // insert teaching lecturer
                $teachingLecturer = TeachingLecturer::create([
                    'id' => Uuid::uuid4(),
                    'evaluation_type_id' => 1,
                    'employee_id' => $request->employee_id[$key],
                    'weekly_schedule_id' => $weeklySchedule->id,
                    'college_class_id' => $collegeClass->id,
                    'credit_total' => $collegeClass->credit_total,
                    'credit_meeting' => $collegeClass->credit_meeting,
                    'credit_practicum' => 0,
                    'credit_practice' => 0,
                    'credit_simulation' => 0,
                    'meeting_plan' => $collegeClass->number_of_meeting,
                    'meeting_realization' => $collegeClass->number_of_meeting,
                    'is_score_entry' => true,
                ]);

                // check if insert to class schedule
                if(isset($request->is_generate_meeting[$key])) {
                    if ($collegeClass->classSchedule()->whereIn('status', ['start', 'done', 'reschedule'])->count()) {
                        throw new Exception('Terdapat kelas yang sudah dimulai, tidak dapat melakukan generate data !');
                    }

                    $collegeClass->classSchedule()->delete();

                    $numberOfMeeting = $collegeClass->number_of_meeting;

                    $classSchedulesToInsert = [];

                    $weeklySchedules = $collegeClass->weeklySchedule()->orderBy('day_id', 'ASC')->get();

                    if (!$weeklySchedules || $weeklySchedules->count() <= 0 || $weeklySchedules == []) {
                        throw new Exception('Tidak ada jadwal mingguan di kelas ini !');
                    }

                    $weeksTotal = ceil($numberOfMeeting / $weeklySchedules->count());

                    $startingPoint = Carbon::parse(AcademicPeriod::where('is_use', true)->first()->college_start_date)->subDay($this->countDateDifference(Carbon::now()->dayOfWeek, $weeklySchedules[0]->day_id));

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
                }
            }
            DB::commit();
            return $this->successResponse('Berhasil menambahkan data', compact('collegeClass'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(CollegeClass $collegeClass)
    {
        // $collegeClass->lecture_system_id = Hashids::encode($collegeClass->lecture_system_id);
        isset($collegeClass->studyProgram->educationLevel) ? $collegeClass->studyProgram->educationLevel : '';
        $collegeClass->academicPeriod;
        $collegeClass->course;
        $collegeClass->lectureSystem;
        // $collegeClass->date_start = !is_null($collegeClass->date_start) ? Carbon::createFromFormat('d M Y', $collegeClass->date_start) : null;
        // $collegeClass->date_end = !is_null($collegeClass->date_end) ? Carbon::createFromFormat('d M Y', $collegeClass->date_end) : null;
        $collegeClass->classParticipantCount = $collegeClass->classParticipant->count();
        return $this->successResponse(null, compact('collegeClass'));
    }

    public function update(CollegeClass $collegeClass, ScheduleRequest $request)
    {
        try {
            $ids = explode('|', $request->course_id);
            $course = Course::whereId($ids[0])->first();
            $request->merge([
                'credit_total' => $course->credit_total == null ? 0 : $course->credit_total,
                'credit_meeting' => $course->credit_meeting == null ? 0 : $course->credit_meeting,
                'credit_practicum' => $course->credit_practicum == null ? 0 : $course->credit_practicum,
                'credit_practice' => $course->credit_practice == null ? 0 : $course->credit_practice,
                'credit_simulation' => $course->credit_simulation == null ? 0 : $course->credit_simulation,
                'lecture_system_id' => Hashids::decode($request->lecture_system_id)[0],
                'curriculum_id' => $ids[1],
                'course_id' => $ids[0]
            ]);
            $collegeClass->update($request->only(['academic_period_id', 'study_program_id', 'course_id', 'curriculum_id', 'lecture_system_id', 'name', 'capacity', 'date_start', 'date_end', 'number_of_meeting', 'credit_total', 'credit_meeting', 'credit_practicum', 'credit_practice', 'credit_simulation', 'case_discussion']));
            return $this->successResponse('Berhasil memperbarui data', compact('collegeClass'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(CollegeClass $collegeClass)
    {
        try {
            $collegeClass->delete();
            return $this->successResponse('Berhasil menghapus data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    private function countDateDifference($x, $y)
    {
        return $y >= $x ? $x - $y : $x - $y - 7;
    }
}
