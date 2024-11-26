<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\TimeSlotRequest;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TimeSlotController extends Controller
{
    public function index()
    {
        return DataTables::of(TimeSlot::query())->editColumn('time', function($data) {
            return Carbon::createFromTimeString($data->time)->format('H:i');
        })->make();
    }

    public function store(TimeSlotRequest $request)
    {
        try {
            $dt = Carbon::createFromTimeString($request->time);
            $timeSlot = TimeSlot::where('time', $dt->format('H:i:s'))->first();
            if($timeSlot != null) {
                return $this->errorResponse(500, 'Terdapat duplikasi data');
            }
            TimeSlot::create([
                'time' => $dt->format('H:i:s'),
                'type' => greet($dt->format('H'))
            ]);
            return $this->successResponse('Berhasil membuat data jam kuliah baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(TimeSlot $timeSlot)
    {
        return $this->successResponse(null, compact('timeSlot'));
    }

    public function destroy(TimeSlot $timeSlot)
    {
        try {
            $timeSlot->delete();
            return $this->successResponse('Berhasil menghapus data jam kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(TimeSlot $timeSlot, TimeSlotRequest $request)
    {
        try {
            $dt = Carbon::createFromTimeString($request->time);
            if($timeSlot->time != $dt->format('H:i:s')) {
                $checkTimeSlot = TimeSlot::where('time', $dt->format('H:i:s'))->first();
                if($checkTimeSlot != null) {
                    return $this->errorResponse(500, 'Terdapat duplikasi data');
                }
            }
            $timeSlot->update([
                'time' => $dt->format('H:i:s'),
                'type' => greet($dt->format('H'))
            ]);
            return $this->successResponse('Berhasil mengupdate data jam kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
