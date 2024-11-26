<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ProvinceRequest;
use App\Models\Region;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProvinceController extends Controller
{
    public function index()
    {
        return DataTables::of(Region::whereLevel(1))->make();
    }

    public function show(Region $region)
    {
        return $this->successResponse(null, compact('region'));
    }

    public function store(ProvinceRequest $request)
    {
        try {
            Region::create([
                'name' => $request->name,
                'parent' => 0,
                'level' => 1
            ]);
            return $this->successResponse('Berhasil membuat data provinsi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Region $region, ProvinceRequest $request)
    {
        try {
            $region->update([
                'name' => $request->name,
                'parent' => 0,
                'level' => 1
            ]);
            return $this->successResponse('Berhasil mengupdate data provinsi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Region $region)
    {
        try {
            $region->delete();
            return $this->successResponse('Berhasil menghapus data provinsi');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
