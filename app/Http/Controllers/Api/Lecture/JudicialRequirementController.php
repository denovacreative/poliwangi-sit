<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\JudicialRequirementRequest;
use App\Models\JudicialRequirement;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class JudicialRequirementController extends Controller
{
    public function index(Request $request)
    {
        $query = JudicialRequirement::query();
        if (!empty($request->judicial_period_id) and $request->judicial_period_id != '' and $request->judicial_period_id != 'all') {
            $query->where('judicial_period_id', $request->judicial_period_id);
        }
        return DataTables::of($query->with('judicialPeriod'))->make();
    }

    public function show(JudicialRequirement $judicialRequirement)
    {
        return $this->successResponse(null, compact('judicialRequirement'));
    }

    public function store(JudicialRequirementRequest $request)
    {
        try {
            JudicialRequirement::create([
                'id' => Uuid::uuid4(),
                'judicial_period_id' => $request->judicial_period,
                'name' => $request->name,
                'is_upload' => isset($request->is_upload) && $request->is_upload == 'on' ? true : false,
            ]);
            return $this->successResponse('Berhasil membuat data syarat yudisium');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(JudicialRequirement $judicialRequirement, JudicialRequirementRequest $request)
    {
        try {
            $judicialRequirement->update([
                'judicial_period_id' => $request->judicial_period,
                'name' => $request->name,
                'is_upload' => isset($request->is_upload) && $request->is_upload == 'on' ? true : false,
            ]);
            return $this->successResponse('Berhasil mengupdate data syarat yudisium');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(JudicialRequirement $judicialRequirement)
    {
        try {
            $judicialRequirement->delete();
            return $this->successResponse('Berhasil menghapus data syarat yudisium');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
