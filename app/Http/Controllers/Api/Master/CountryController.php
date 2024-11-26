<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class CountryController extends Controller
{
    public function index()
    {
        return DataTables::of(Country::query())->make();
    }

    public function show(Country $country)
    {
        return $this->successResponse(null, compact('country'));
    }

    public function store(CountryRequest $request)
    {
        try {
            Country::create($request->only(['name', 'id']));
            return $this->successResponse('Berhasil membuat data negara baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Country $country, CountryRequest $request)
    {
        try {
            $country->update($request->only(['name', 'id']));
            return $this->successResponse('Berhasil mengupdate data neagara!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Country $country)
    {
        try {
            $country->delete();
            return $this->successResponse('Berhasil menghapus data negara!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
