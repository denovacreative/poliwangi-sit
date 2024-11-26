<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\TransportationRequest;
use App\Models\Transportation;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransportationController extends Controller
{
    public function index()
    {
        return DataTables::of(Transportation::all())->make();
    }

    public function show(Transportation $transportation)
    {
        return $this->successResponse(null, compact('transportation'));
    }

    public function store(TransportationRequest $request)
    {
        try {
            Transportation::create($request->only(['code', 'name']));
            return $this->successResponse('Berhasil membuat data transportasi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Transportation $transportation, TransportationRequest $request)
    {
        try {
            $transportation->update($request->only(['code', 'name']));
            return $this->successResponse('Berhasil mengupdate data transportasi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Transportation $transportation)
    {
        try {
            $transportation->delete();
            return $this->successResponse('Berhasil menghapus data transportasi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
