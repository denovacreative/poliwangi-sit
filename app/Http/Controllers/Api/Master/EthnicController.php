<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\EthnicRequest;
use App\Models\Ethnic;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class EthnicController extends Controller
{
    public function index()
    {
        return DataTables::of(Ethnic::query())->make();
    }

    public function store(EthnicRequest $request)
    {
        try {
            Ethnic::create($request->only(['name']));
            return $this->successResponse('Berhasil membuat data suku baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(Ethnic $ethnic)
    {
        return $this->successResponse(null, compact('ethnic'));
    }

    public function destroy(Ethnic $ethnic)
    {
        try {
            $ethnic->delete();
            return $this->successResponse('Berhasil menghapus data suku!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Ethnic $ethnic, EthnicRequest $request)
    {
        try {
            $ethnic->update($request->only(['name']));
            return $this->successResponse('Berhasil mengupdate data suku!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
