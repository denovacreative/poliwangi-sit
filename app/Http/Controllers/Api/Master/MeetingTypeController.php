<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\MeetingTypeRequest;
use App\Models\MeetingType;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class MeetingTypeController extends Controller
{
    public function index()
    {
        return DataTables::of(MeetingType::query())->addColumn('type_name', function($meetingType) {
            switch ($meetingType->type) {
                case 'college':
                    return 'Kuliah';
                case 'mid_exam':
                    return 'UTS';
                case 'final_exam':
                    return 'UAS';
                case 'none':
                default:
                    return '-';
            }
        })->make();
    }

    public function store(MeetingTypeRequest $request)
    {
        try {
            MeetingType::create($request->only(['code', 'name', 'alias', 'type', 'is_presence', 'is_exam']));
            return $this->successResponse('Berhasil membuat data jenis pertemuan baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(MeetingType $meetingType)
    {
        return $this->successResponse(null, compact('meetingType'));
    }

    public function destroy(MeetingType $meetingType)
    {
        try {
            $meetingType->delete();
            return $this->successResponse('Berhasil menghapus data jenis pertemuan!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(MeetingType $meetingType, MeetingTypeRequest $request)
    {
        try {
            if (!$request->has('is_presence')) {
                $request->merge(['is_presence' => false]);
            }
            $meetingType->update($request->only([
                'code', 'name', 'alias', 'type', 'is_presence', 'is_exam'
            ]));
            return $this->successResponse('Berhasil mengupdate data jenis pertemuan!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
