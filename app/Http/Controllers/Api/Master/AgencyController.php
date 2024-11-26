<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\AgencyRequest;
use App\Models\Agency;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AgencyController extends Controller
{
    public function index()
    {
        return DataTables::of(Agency::all())->make();
    }

    public function show(Agency $agency)
    {
        return $this->successResponse(null, compact('agency'));
    }

    public function store(AgencyRequest $request)
    {
        try {
            Agency::create($request->only(['name', 'phone_number', 'fax', 'email', 'website', 'address']));
            return $this->successResponse('Berhasil membuat data instansi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Agency $agency, AgencyRequest $request)
    {
        try {
            $agency->update($request->only(['name', 'phone_number', 'fax', 'email', 'website', 'address']));
            return $this->successResponse('Berhasil mengupdate data instansi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Agency $agency)
    {
        try {
            $agency->delete();
            return $this->successResponse('Berhasil menghapus data instansi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
