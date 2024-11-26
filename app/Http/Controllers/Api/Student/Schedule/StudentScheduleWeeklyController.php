<?php

namespace App\Http\Controllers\Api\Student\Schedule;

use App\Http\Controllers\Controller;
use App\Models\ClassParticipant;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class StudentScheduleWeeklyController extends Controller
{
    public function index()
    {
        $weeksData = collect(range(0, 6))->map(function ($day, $key) {
            return Carbon::now()->startOfWeek()->subDay(0 - $day);
        })->mapWithKeys(function ($data) {
            return [$data->format('Y-m-d') => [
                'day_name' => $data->translatedFormat('l'),
                'formatted_date' => $data->translatedFormat('d-m-Y'),
                'schedules' => collect([]),
            ]];
        })->toArray();

        $studentClasses = ClassSchedule::whereHas('collegeClass', function($cc) {
            $cc->whereHas('academicPeriod', function($ap) {
                $ap->where('is_use', true);
            })->whereHas('classParticipant', function($cp) {
                $cp->where('student_id', Auth::user()->userable->id);
            });
        })->whereIn('date', array_keys($weeksData))
          ->with(['collegeClass.course', 'room', 'employee'])
          ->orderBy('time_start', 'ASC')
          ->get()
          ->mapToGroups(function($data) {
            return [$data->date => $data];
        });

        foreach ($studentClasses as $key => $sc) $weeksData[$key]['schedules'] = $sc;

        return $this->successResponse('Berhasil mendapatkan jadwal mingguan', [
            'weekly_schedule' => $weeksData,
            // 'sc' => $studentClasses
        ]);
    }
}
