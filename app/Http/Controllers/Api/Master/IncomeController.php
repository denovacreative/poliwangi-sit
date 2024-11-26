<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use DataTables;
use Exception;

class IncomeController extends Controller
{

    public function index()
    {
        return DataTables::of(Income::query())->make();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        try {
            Income::create($request->only('name'));

            return $this->successResponse('Berhasil menambahkan data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(Income $income)
    {
        try {
            return $this->successResponse(null, compact('income'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Request $request, Income $income)
    {
        $request->validate([
            'name' => 'required'
        ]);

        try {
            $income->update([
                'name' => $request->name
            ]);

            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Income $income)
    {
        try {
            $income->delete();
            return $this->successResponse('Berhasil menghapus data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
