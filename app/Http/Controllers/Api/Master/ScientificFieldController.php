<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ScientificFieldRequest;
use App\Models\ScientificField;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class ScientificFieldController extends Controller
{
    public function index()
    {
        return DataTables::of(ScientificField::query())->make();
    }

    public function store(ScientificFieldRequest $request)
    {
        try {
            ScientificField::create($request->only(['code', 'name']));
            return $this->successResponse('Berhasil membuat data bidang ilmu baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(ScientificField $scientificField)
    {
        return $this->successResponse(null, compact('scientificField'));
    }

    public function destroy(ScientificField $scientificField)
    {
        try {
            $scientificField->delete();
            return $this->successResponse('Berhasil menghapus data bidang ilmu!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(ScientificField $scientificField, ScientificFieldRequest $request)
    {
        try {
            $scientificField->update($request->only([
                'code', 'name'
            ]));
            return $this->successResponse('Berhasil mengupdate data bidang ilmu!');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
