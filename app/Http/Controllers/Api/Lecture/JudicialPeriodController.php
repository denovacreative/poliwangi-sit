<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\JudicialPeriodRequest;
use App\Models\JudicialPeriod;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class JudicialPeriodController extends Controller
{
    public function index()
    {
        return DataTables::of(JudicialPeriod::with('academicPeriod'))->addColumn('academic_period', function ($data) {
            return $data->academicPeriod->name;
        })->editColumn('date', function ($data) {
            return Carbon::parse($data->date)->isoFormat('D MMMM Y');
        })->editColumn('date_start', function ($data) {
            return Carbon::parse($data->date_start)->isoFormat('D MMMM Y');
        })->editColumn('date_end', function ($data) {
            return Carbon::parse($data->date_end)->isoFormat('D MMMM Y');
        })->make();
    }

    public function show(JudicialPeriod $judicialPeriod)
    {
        return $this->successResponse(null, compact('judicialPeriod'));
    }

    public function store(JudicialPeriodRequest $request)
    {
        try {
            $dateStart = Carbon::createFromFormat('Y-m-d', $request->date_start);
            $dateEnd = Carbon::createFromFormat('Y-m-d', $request->date_end);
            if ($dateEnd->lt($dateStart)) {
                return $this->errorResponse(500, 'Tanggal Akhir Daftar tidak boleh lebih kecil dari Tanggal Awal Daftar');
            }
            JudicialPeriod::create([
                'id' => Uuid::uuid4(),
                'academic_period_id' => $request->academic_period,
                'periode' => $request->periode,
                'name' => $request->name,
                'date' => $request->date,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
            ]);
            return $this->successResponse('Berhasil membuat data periode yudisium');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(JudicialPeriod $judicialPeriod, JudicialPeriodRequest $request)
    {
        try {
            $judicialPeriod->update([
                'academic_period_id' => $request->academic_period,
                'periode' => $request->periode,
                'name' => $request->name,
                'date' => $request->date,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
            ]);
            return $this->successResponse('Berhasil mengupdate data periode yudisium');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(JudicialPeriod $judicialPeriod)
    {
        try {
            $judicialPeriod->delete();
            return $this->successResponse('Berhasil menghapus data periode yudisium');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
