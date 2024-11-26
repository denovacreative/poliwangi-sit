<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\RoomRequest;
use App\Models\Major;
use App\Models\Room;
use App\Models\StudyProgram;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        if (!empty($request->major) and $request->major != '' and $request->major != 'all') {
            $query->where(['unitable_type' => Major::class, 'unitable_id' => $request->major]);
        }

        if (!empty($request->study_program) and $request->study_program != '' and $request->study_program != 'all') {
            $query->where(['unitable_type' => StudyProgram::class, 'unitable_id' => $request->study_program]);
        }

        return DataTables::of($query)
            ->addColumn('unit', function ($data) {
                $q = null;
                if (!is_null($data->unitable_type) && $data->unitable_type == Major::class) {
                    $major = Major::find($data->unitable_id);
                    $q = is_null($major) ? null : $major->name;
                } else if (!is_null($data->unitable_type) && $data->unitable_type == StudyProgram::class) {
                    $studyProgram = StudyProgram::where('id', $data->unitable_id)->with(['educationLevel'])->first();
                    $q = is_null($studyProgram) ? null : $studyProgram->educationLevel->code . ' - ' . $studyProgram->name;
                }

                return  $q;
            })
            ->make();
    }

    public function show(Room $room)
    {
        return $this->successResponse(null, compact('room'));
    }

    public function store(RoomRequest $request)
    {
        try {
            if (!empty($request->unit_type)) {
                $request->merge([
                    'unitable_type' => $request->unit_type == 'major' ? Major::class : StudyProgram::class,
                    'unitable_id' => $request->unit
                ]);

                Room::create($request->only(['unitable_type', 'unitable_id', 'code', 'name', 'location', 'capacity', 'type', 'description', 'is_active']));
            } else {
                Room::create($request->only(['code', 'name', 'location', 'capacity', 'type', 'description', 'is_active']));
            }

            return $this->successResponse('Berhasil membuat data ruang kuliah baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Room $room, RoomRequest $request)
    {
        try {
            if (!empty($request->unit_type)) {
                $request->merge([
                    'unitable_type' => $request->unit_type == 'major' ? Major::class : StudyProgram::class,
                    'unitable_id' => $request->unit
                ]);

                $room->update($request->only(['unitable_type', 'unitable_id', 'code', 'name', 'location', 'capacity', 'type', 'description', 'is_active']));
            } else {
                $room->update($request->only(['code', 'name', 'location', 'capacity', 'type', 'description', 'is_active']));
            }

            return $this->successResponse('Berhasil mengupdate data ruang kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Room $room)
    {
        try {
            $room->delete();
            return $this->successResponse('Berhasil menghapus data ruang kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
