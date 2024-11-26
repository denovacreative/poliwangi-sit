<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UniversityRequest;
use App\Models\University;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;

class UniversityController extends Controller
{
    public function index()
    {
        return DataTables::of(University::query())->make();
    }

    public function store(UniversityRequest $request)
    {
        try {
            University::create($request->only(['code', 'name', 'phone_number', 'fax', 'email', 'website', 'address']));
            return $this->successResponse('Berhasil membuat data universitas luar baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(University $university)
    {
        return $this->successResponse(null, compact('university'));
    }

    public function destroy(University $university)
    {
        try {
            $university->delete();
            return $this->successResponse('Berhasil menghapus data universitas luar!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(University $university, UniversityRequest $request)
    {
        try {
            $university->update($request->only([
                'code', 'name', 'phone_number', 'fax', 'email', 'website', 'address'
            ]));
            return $this->successResponse('Berhasil mengupdate data universitas luar!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
