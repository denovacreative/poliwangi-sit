<?php

namespace App\Http\Controllers\Api\Student\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\Announcement;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use App\Models\StudentCollegeActivity;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{

    public function index(Request $request)
    {
        // if ($request->has('class_schedule_date') and $request->class_schedule_date != '') {
        //     $this->getClassSchedules($request->class_schedule_date);
        // }
        $data = [
            'studentCollegeActivity' => $this->getStudentCollegeActivity(),
            'creditTotal' => $this->getCreditTotal(),
            'announcements' => $this->getAnnouncements(),
            'progressProfile' => $this->getProgressProfile(),
            'heRegistrationState' => $this->getHeRegistrationState(),
            // 'class_schedule' => $this->getClassSchedules()
        ];

        return $this->successResponse(null, $data);
    }

    private function getCreditTotal()
    {
        $credit = 0;
        $student = getInfoLogin()->userable;
        foreach ($student->classParticipant as $item) {
            $credit += $item->collegeClass->credit_total;
        }
        return $credit;
    }

    private function getStudentCollegeActivity()
    {
        $countLongStudy = getInfoLogin()->userable->studyProgram->educationLevel->educationLevelSetting->study;
        $studentCollegeActivity = StudentCollegeActivity::where('student_id', getInfoLogin()->userable_id)->orderBy('academic_period_id', 'asc')->get();
        $lastStudentCollegeActivity = [];
        $credit = [];
        $grade = [];

        for ($i = 0; $i < $countLongStudy; $i++) {
            $gradeSemester = 0;
            if ($i + 1 == $studentCollegeActivity->count()) {
                $lastStudentCollegeActivity = $studentCollegeActivity[$i];
            }

            if ($i + 1 <= $studentCollegeActivity->count()) {
                $gradeSemester = (float) $studentCollegeActivity[$i]->grade_semester;
            }

            $credit[] = $i + 1;
            $grade[] = $gradeSemester;
        }
        $lastStudentCollegeActivity['last_semester'] = $studentCollegeActivity->count();

        return compact('credit', 'grade', 'lastStudentCollegeActivity');
    }

    private function getAnnouncements()
    {
        $announcements = Announcement::where('is_active', true)->where(function ($q) {
            $q->where('user_id', Auth::user()->id)->orWhere('role_id', Auth::user()->roles()->get()[0]->id);
        })->orderBy('is_priority', 'DESC')->orderBy('created_at', 'DESC')->take(5)->get();

        return compact('announcements');
    }

    private function getProgressProfile()
    {
        $students = Auth::user()->userable()->with(['studyProgram.educationLevel', 'academicPeriod'])->first();
        $countAll = 0;
        $countInvalid = 0;
        foreach ($students->toArray() as $key => $item) {
            if (!in_array($key, ['created_at', 'updated_at', 'is_valid', 'entry_date'])) {
                if (!is_null($item)) {
                    $countInvalid += 1;
                }

                $countAll += 1;
            }
        }

        return ceil(($countInvalid / $countAll) * 100);
    }

    private function getHeRegistrationState()
    {
        // Carbon::;
        // CarbonPeriod::create('');
        $dateNow = Carbon::now();
        $heRegistrationState = AcademicPeriod::select(['heregistration_start_date', 'heregistration_end_date'])->where('is_use', true)->where('heregistration_start_date', '<=', $dateNow)->where('heregistration_end_date', '>=', $dateNow)->with(['heregistration' => function ($q) {
            $q->where('student_id', getInfoLogin()->userable->id);
        }])->first();
        if (!is_null($heRegistrationState)) {
            $heRegistrationState->heregistration_start_date = Carbon::createFromFormat('Y-m-d', $heRegistrationState->heregistration_start_date)->format('d-m-Y');
            $heRegistrationState->heregistration_end_date = Carbon::createFromFormat('Y-m-d', $heRegistrationState->heregistration_end_date)->format('d-m-Y');
        }

        return $heRegistrationState;
    }

    public function getClassSchedules(Request $request)
    {
        $classSchedules = ClassSchedule::whereHas('collegeClass', function ($cc) {
            $cc->where('academic_period_id', getActiveAcademicPeriod()->id);
            $cc->whereHas('classParticipant', function ($cp) {
                $cp->where('student_id', getInfoLogin()->userable->id);
            });
        })
            ->with(['collegeClass.course', 'room', 'employee'])
            ->with(['collegeClass' => function ($q) {
                $q->withCount(['presence' => function ($q) {
                    $q->where(['student_id' => getInfoLogin()->userable->id, 'status' => 'H']);
                }]);
            }]);

        if ($request->has('date') and $request->date != '') {
            $newDate = Carbon::createFromFormat('D, d/m/Y', $request->date)->format('Y-m-d');
            $classSchedules = $classSchedules
                ->where('date', $newDate)
                ->orderBy('time_start', 'asc')
                ->get();
        } else {
            $dateNow = Carbon::now();
            $classSchedules = $classSchedules
                ->where('date', $dateNow)
                ->orderBy('time_start', 'asc')
                ->get();
        }
        return $this->successResponse(null, ['class_schedule' => $classSchedules]);
    }
}
