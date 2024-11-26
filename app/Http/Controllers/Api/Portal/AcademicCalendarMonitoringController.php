<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\AcademicPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AcademicCalendarMonitoringController extends Controller
{
    public function index(Request $request)
    {
        try {
            $academicCalendars = AcademicCalendar::query();
            $initialDate = null;
            if (!empty($request->academic_period) and $request->academic_period != '') {
                $academicPeriod = AcademicPeriod::find($request->academic_period);
                $year = $academicPeriod->academic_year_id;
                $academicCalendars->where('academic_period_id', $request->academic_period);
                $initialDate = Carbon::createFromDate(trim($year))->toDateString();
            }
            $academicCalendars = $academicCalendars->with(['academicActivity', 'academicPeriod'])->get();
            return $this->successResponse(null, compact('academicCalendars', 'initialDate'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
