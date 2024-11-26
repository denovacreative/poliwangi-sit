<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\CollegeClass;
use App\Models\Presence;
use App\Models\UniversityProfile;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Carbon;

class PresenceController extends Controller
{

    public function getCourse(Request $request)
    {
        $courses = Course::whereHas('collegeClass', function ($q) use ($request) {
            $q->where($request->only(['academic_period_id', 'study_program_id']));
        })->get();

        return $this->successResponse(null, compact('courses'));
    }

    public function getCollegeClass(Request $request)
    {
        $collegeClasses = CollegeClass::where($request->only(['academic_period_id', 'study_program_id', 'course_id']))->with(['lectureSystem'])->withCount(['classParticipant'])->get();
        return $this->successResponse(null, compact('collegeClasses'));
    }

    public function getClassParticipant(Request $request)
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
                    $temp['meeting_of_' . $schedule->meeting_number] = !is_null($presence) ? [$schedule->status != 'done', $presence->status] : [$schedule->status != 'done', '0'];
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
            DB::beginTransaction();
            $classSchedule = ClassSchedule::where($request->only('college_class_id'))->orderBy('meeting_number', 'asc')->get();
            $collections = [];
            foreach ($request->student_id as $key => $item) {
                foreach ($classSchedule as $schedule) {
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

            Presence::insert($collections);
            DB::commit();

            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }


    public function print(Request $request)
    {
        try {
            $presences = [];

            $query = Presence::with(['student', 'classSchedule', 'classSchedule.employee', 'collegeClass.studyProgram', 'collegeClass.academicPeriod', 'collegeClass.course']);

            if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id !=  'all') {
                $query->whereHas('collegeClass', function ($q) use ($request) {
                    $q->where('study_program_id', $request->study_program_id);
                });
            }

            if ($request->has('academic_period_id') && $request->academic_period_id != null) {
                $query->whereHas('collegeClass', function ($q) use ($request) {
                    $q->where('academic_period_id', $request->academic_period_id);
                });
            }



            if ($request->has('employee_id') && $request->employee_id != null) {
                $query->whereHas('classSchedule', function ($q) use ($request) {
                    $q->where('employee_id', $request->employee_id);
                });
            }

            foreach ($query->get() as $key => $data) {
                $courseName = $data->collegeClass->course->name;
                if (!isset($presences[$courseName])) {
                    $presences[$courseName] = [
                        'courseName' => $courseName,
                        'academicPeriod' => $data->collegeClass->academicPeriod->name,
                        'studyProgram' => $data->collegeClass->studyProgram->educationLevel->code . ' - ' . $data->collegeClass->studyProgram->name,
                        'credit' => $data->collegeClass->credit_total,
                        'employees' => [],
                        'number_of_meet' =>  $data->collegeClass->number_of_meeting,
                        'presence' => []
                    ];
                }

                $employeeId = $data->classSchedule->employee->id;

                // Cek keberadaan employee berdasarkan ID
                $employeeExists = false;
                foreach ($presences[$courseName]['employees'] as $employee) {
                    if ($employee['id'] === $employeeId) {
                        $employeeExists = true;
                        break;
                    }
                }

                // Jika employee belum ada, tambahkan ke array employees
                if (!$employeeExists) {
                    $employeeData = [
                        'id' => $employeeId,
                        'name' => $data->classSchedule->employee->name,
                        'front_title' => $data->classSchedule->employee->front_title,
                        'back_title' => $data->classSchedule->employee->back_title
                    ];
                    $presences[$courseName]['employees'][] = $employeeData;
                }
                $presences[$courseName]['presence'][] = $data;
            }

            return view('print.presence-recap', [
                'datas' => $presences,
                'universitasProfile' => UniversityProfile::first(),
                'title' => 'Laporan Rekap Presensi Mahasiswa | Poliwangi'
            ]);

        } catch (Exception $e) {
            return abort(404);
        }
    }
}
