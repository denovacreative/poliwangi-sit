<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profession;
use App\Http\Requests\Master\ProfessionRequest;
use DataTables;

class ProfessionController extends Controller
{

    public function index() 
    {
        return DataTables::of(Profession::query())->make();
    }

    public function store(ProfessionRequest $request)
    {
        try {
            Profession::create($request->only(['code', 'name']));

            return $this->successResponse('Berhasil menambahkan data');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(Profession $profession)
    {
        try {
            return $this->successResponse(null, compact('profession'));
        } catch(Exception $e) {
            return exceptionResponse($e);
        }
    }

    public function update(ProfessionRequest $request, Profession $profession)
    {
        try {
            $profession->update($request->only('code', 'name'));

            return $this->successResponse('Berhasil mengubah data');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Profession $profession)
    {
        try {
            $profession->delete();

            return $this->successResponse('Berhasil menghapus data');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    
}
