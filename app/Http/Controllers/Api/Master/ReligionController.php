<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ReligionRequest;
use App\Models\Religion;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class ReligionController extends Controller
{
    public function index()
    {
        return DataTables::of(Religion::query())->make();
    }

    public function store(ReligionRequest $request)
    {
        try {
            Religion::create($request->only(['name']));
            return $this->successResponse('Berhasil membuat data agama baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(Religion $religion)
    {
        return $this->successResponse(null, compact('religion'));
    }

    public function destroy(Religion $religion)
    {
        try {
            $religion->delete();
            return $this->successResponse('Berhasil menghapus data agama!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Religion $religion, ReligionRequest $request)
    {
        try {
            $religion->update($request->only(['name']));
            return $this->successResponse('Berhasil mengupdate data agama!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
