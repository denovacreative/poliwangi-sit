<?php

namespace App\Http\Controllers\Api\StudentAffairs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAffairs\ScholarshipRequest;
use App\Models\Scholarship;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholarship::with(['periodStart', 'periodEnd', 'scholarshipType', 'studentScholarship']);
        if ($request->has('scholarship_type_id') && $request->scholarship_type_id != '' && $request->scholarship_type_id != 'all') {
            $id = Hashids::decode($request->scholarship_type_id)[0];
            $query->where('scholarship_type_id', $id);
        }
        return DataTables::of($query)->addColumn('student_count', function ($data) {
            return count($data->studentScholarship);
        })->addColumn('sum_amount', function ($data) {
            return $data->studentScholarship->sum('amount');
        })->addColumn('period_date', function ($data) {
            return Carbon::parse($data->date_start)->isoFormat('D MMM Y') . ' s.d ' . Carbon::parse($data->date_end)->isoFormat('D MMM Y');
        })->make();
    }

    public function show(Scholarship $scholarship)
    {
        $data = [
            'scholarship' => $scholarship,
            'scholarship_type_id' => Hashids::encode($scholarship->scholarship_type_id)
        ];
        return $this->successResponse(null, compact('data'));
    }

    public function store(ScholarshipRequest $request)
    {
        try {
            $dateStart = Carbon::parse($request->date_start)->format('Y-m-d');
            $dateEnd = Carbon::parse($request->date_end)->format('Y-m-d');
            if ($request->period_start > $request->period_end) {
                return $this->errorResponse(500,  'Periode mulai tidak boleh lebih besar dari periode akhir');
            }
            if ($dateStart >= $dateEnd) {
                return $this->errorResponse(500,  'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            }
            Scholarship::create([
                'id' => Uuid::uuid4(),
                'name' => $request->name,
                'period_start_id' => $request->period_start,
                'period_end_id' => $request->period_end,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'total_budget' => $request->amount,
                'scholarship_type_id' => Hashids::decode($request->scholarship_type)[0],
            ]);
            return $this->successResponse('Berhasil membuat beasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Scholarship $scholarship, ScholarshipRequest $request)
    {
        try {
            $dateStart = Carbon::parse($request->date_start)->format('Y-m-d');
            $dateEnd = Carbon::parse($request->date_end)->format('Y-m-d');
            if ($request->period_start > $request->period_end) {
                return $this->errorResponse(500,  'Periode mulai tidak boleh lebih besar dari periode akhir');
            }
            if ($dateStart >= $dateEnd) {
                return $this->errorResponse(500,  'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            }
            $scholarship->update([
                'name' => $request->name,
                'period_start_id' => $request->period_start,
                'period_end_id' => $request->period_end,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'total_budget' => $request->amount,
                'scholarship_type_id' => Hashids::decode($request->scholarship_type)[0],
            ]);
            return $this->successResponse('Berhasil mengupdate beasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Scholarship $scholarship)
    {
        try {
            $scholarship->delete();
            return $this->successResponse('Berhasil menghapus beasiswa');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
