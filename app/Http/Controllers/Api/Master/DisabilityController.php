<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\DisabilityRequest;
use App\Models\Disability;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DisabilityController extends Controller
{
    public function index()
    {
        return DataTables::of(Disability::query())->make();
    }

    public function show(Disability $disability)
    {
        return $this->successResponse(null, compact('disability'));
    }

    public function store(DisabilityRequest $request)
    {
        try {
            Disability::create($request->only(['name']));
            return $this->successResponse('Berhasil membuat data kebutuhan khusus');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Disability $disability, DisabilityRequest $request)
    {
        try {
            $disability->update($request->only(['name']));
            return $this->successResponse('Berhasil mengupdate data kebutuhan khusus');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Disability $disability)
    {
        try {
            $disability->delete();
            return $this->successResponse('Berhasil menghapus data kebutuhan khusus');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
