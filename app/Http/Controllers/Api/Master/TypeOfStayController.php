<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\TypeOfStayRequest;
use App\Models\TypeOfStay;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TypeOfStayController extends Controller
{
    public function index()
    {
        return DataTables::of(TypeOfStay::all())->make();
    }

    public function show(TypeOfStay $typeOfStay)
    {
        return $this->successResponse(null, compact('typeOfStay'));
    }

    public function store(TypeOfStayRequest $request)
    {
        try {
            TypeOfStay::create($request->only(['name']));
            return $this->successResponse('Berhasil membuat data jenis tinggal');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(TypeOfStay $typeOfStay, TypeOfStayRequest $request)
    {
        try {
            $typeOfStay->update($request->only(['name']));
            return $this->successResponse('Berhasil mengupdate data jenis tinggal');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(TypeOfStay $typeOfStay)
    {
        try {
            $typeOfStay->delete();
            return $this->successResponse('Berhasil menghapus data jenis tinggal');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
