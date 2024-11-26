<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\AcademicCalendarRequest;
use App\Models\AcademicCalendar;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class AcademicCalendarController extends Controller
{
    public function index(Request $request)
    {

        $query = AcademicCalendar::with(['academicPeriod', 'academicActivity']);

        if (!is_null($request->academic_period_id) && $request->academic_period_id != '' && $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }

        return DataTables::of($query)
            ->addColumn('date_start_formatted', function ($academicCalendar) {
                return Carbon::parse($academicCalendar->date_start)->format('d-m-Y');
            })
            ->addColumn('date_end_formatted', function ($academicCalendar) {
                return Carbon::parse($academicCalendar->date_end)->format('d-m-Y');
            })
            ->make();
    }

    public function show(AcademicCalendar $academicCalendar)
    {
        $academicCalendar->hashed_academic_activity_id = Hashids::encode($academicCalendar->academic_activity_id);
        return $this->successResponse(null, compact('academicCalendar'));
    }

    public function store(AcademicCalendarRequest $request)
    {
        try {
            $startDate = Carbon::parse($request->date_start)->format('Y-m-d');
            $endDate = Carbon::parse($request->date_end)->format('Y-m-d');
            if ($startDate >= $endDate) {
                return $this->errorResponse(500, 'Periode tanggal tidak valid');
            }
            $request->merge(['academic_activity_id' => Hashids::decode($request->academic_activity_id)[0], 'academic_period_id' => Hashids::decode($request->academic_period_id)[0]]);
            AcademicCalendar::create($request->only([
                'name', 'date_start', 'date_end', 'is_national_holiday', 'is_academic_holiday', 'academic_period_id', 'academic_activity_id'
            ]));
            return $this->successResponse('Berhasil membuat data kalender akademik baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(AcademicCalendar $academicCalendar, AcademicCalendarRequest $request)
    {
        try {
            $startDate = Carbon::parse($request->date_start)->format('Y-m-d');
            $endDate = Carbon::parse($request->date_end)->format('Y-m-d');
            if ($startDate >= $endDate) {
                return $this->errorResponse(500, 'Periode tanggal tidak valid');
            }
            if ($request->has('academic_activity_id')) {
                $request->merge(['academic_activity_id' => Hashids::decode($request->academic_activity_id)[0], 'academic_period_id' => Hashids::decode($request->academic_period_id)[0]]);
            }

            if (!$request->has('is_national_holiday')) $request->merge(['is_national_holiday' => false]);
            if (!$request->has('is_academic_holiday')) $request->merge(['is_academic_holiday' => false]);

            $academicCalendar->update($request->only(['name', 'date_start', 'date_end', 'is_national_holiday', 'is_academic_holiday', 'academic_period_id', 'academic_activity_id']));
            return $this->successResponse('Berhasil mengupdate data kalender akademik!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(AcademicCalendar $academicCalendar)
    {
        try {
            $academicCalendar->delete();
            return $this->successResponse('Berhasil menghapus data kalender akademik!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
