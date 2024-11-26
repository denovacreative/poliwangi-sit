<?php

namespace App\Http\Controllers\Api\Lecturer\Schedule;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LecturerWeeklyScheduleController extends Controller
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

        $lecturerClasses = ClassSchedule::whereHas('collegeClass', function ($q) {
            $q->whereHas('teachingLecturer', function ($q) {
                $q->where('employee_id', getInfoLogin()->userable->id);
            })->where('academic_period_id', getActiveAcademicPeriod()->id);
        })
            ->whereIn('date', array_keys($weeksData))
            ->with(['collegeClass.course', 'room', 'employee'])
            ->orderBy('time_start', 'ASC')
            ->get()
            ->mapToGroups(function ($data) {
                return [$data->date => $data];
            });
        foreach ($lecturerClasses as $key => $lc) $weeksData[$key]['schedules'] = $lc;

        return $this->successResponse(null, ['weekly_schedule' => $weeksData]);
    }
}
