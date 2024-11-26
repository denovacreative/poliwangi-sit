<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CityRequest;
use App\Models\Region;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    public function index()
    {
        return DataTables::of(Region::whereLevel(2)->with(['parent']))->make();
    }

    public function show(Region $region)
    {
        return $this->successResponse(null, compact('region'));
    }

    public function store(CityRequest $request)
    {
        try {
            Region::create([
                'name' => $request->name,
                'parent' => $request->province,
                'level' => 2
            ]);
            return $this->successResponse('Berhasil membuat data kota');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Region $region, CityRequest $request)
    {
        try {
            $region->update([
                'name' => $request->name,
                'parent' => $request->province,
                'level' => 2
            ]);
            return $this->successResponse('Berhasil mengupdate data kota');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Region $region)
    {
        try {
            $region->delete();
            return $this->successResponse('Berhasil menghapus data kota');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
