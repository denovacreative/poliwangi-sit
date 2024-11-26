<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\SubDistrictRequest;
use App\Models\Region;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class SubDistrictController extends Controller
{
    public function index()
    {
        return DataTables::of(Region::where('level', 3)->with('parent'))->addColumn('district_name', function($data) {
            // return $data->parent ? $data->parent->name : '-';
        })->make();
    }

    public function show(Region $region)
    {
        return $this->successResponse(null, compact('region'));
    }

    public function store(SubDistrictRequest $request)
    {
        try {
            Region::create(array_merge($request->only(['name', 'parent']), ['level' => 3]));
            return $this->successResponse('Berhasil membuat data kecamatan baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(SubDistrictRequest $request, Region $region)
    {
        try {
            $region->update(array_merge($request->only(['name', 'parent']), ['level' => 3]));
            return $this->successResponse('Berhasil mengupdate data kecamatan!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Region $region)
    {
        try {
            $region->delete();
            return $this->successResponse('Berhasil menghapus data kecamatan!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
